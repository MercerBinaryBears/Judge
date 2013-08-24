<?php
class JudgeController extends BaseController {
	/**
	 * The index for a judge. Display the current unjudged problems, and allows
	 * the judge to claim that problem.
	 */
	public function index() {
		return View::make('solutions_judge')
			->with('unjudged_solutions', Solution::forCurrentContest()->unjudged()->unclaimed()->get())
			->with('claimed_solutions', Solution::forCurrentContest()->where('claiming_judge_id', Sentry::getUser()->id)->get());
	}

	/**
	 * Shows the update form for a submission (only viewable by judges), also
	 * forces a judge to "claim" the submission, so no other judge can edit
	 * it. If a judge visits an already claimed problem, they should be
	 * redirected to the judgeIndex page.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// get the solution passed
		$solution = Solution::find($id);

		// claim the problem, reporting errors if the user couldn't claim it
		if(!$solution->claim()) {
			Session::flash('error', 'You cannot claim that solution');
			return Redirect::route('judge_index');
		}

		// All saved well, show the judge the form for them to judge
		return View::make('forms.edit_solution')
			->with('solution', $solution)
			->with('solution_states', SolutionState::lists('name','id'));
	}

	/**
	 * Updates the status of a submission, only allowed by judges
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$solution = Solution::find($id);

		// Check that this current judge has claimed the problem
		// Check validation on save, and report errors if any. There shouldn't be, but
		// malicious input could cause it.
		if($solution->ownedByCurrentUser()) {
			$solution->solution_state_id = Input::get('solution_state_id');
			if(!$solution->save()) {
				Session::flash('error', $s->errors());
			}
		}
		else {
			Session::flash('error', 'You are not the claiming judge for this problem any more');
		}

		return Redirect::route('judge_index');
	}

	/**
	 * Unclaims a solution if the current user had claimed that solution
	 *
	 * @param int $id The id of the solution to unclaim
	 */
	public function unclaim($id) {
		$solution = Solution::find($id);

		if(!$solution->unclaim()) {
			Session::flash('error', 'You are not the claiming judge for this problem');
		}
		return Redirect::route('judge_index');
	}

	/**
	 * Creates a download package for judges to judge a problem
	 */
	public function package($id) {
		// get the requested solution
		$solution = Solution::find($id);

		/*
		|-------------------------------------------------------------------------
		| Build paths for each of the download components
		|-------------------------------------------------------------------------
		|
		| 1) The actual solution code, in its original name the client provided
		| 2) The judging input
		| 3) The judging output
		| 4) A judge helper script that essentially automates the
		|    build, checks for memory errors and exceptions
		*/
		$solution_real_path = $solution->getPathForResource('solution_code');
		$judging_input_real_path = $solution->problem->getPathForResource('judging_input');
		$judging_output_real_path = $solution->problem->getPathForResource('judging_output');


		// the path for a zip is built off of the solution id and a timestamp
		$zip_path = "/tmp/solution_" . $solution->id . "_" . time();


		// attempt to create the zip file
		$zip_file = new ZipArchive();

		// TODO: Generalize these calls

		$open_result = $zip_file->open($zip_path, ZIPARCHIVE::CREATE);
		if($open_result !== true) {
			return "Could not start the zip file at $zip_path, " . $this->ZipStatusString($open_result);
		}

		// now, add the files to the archive
		$add_result = $zip_file->addFile($solution_real_path, $solution->solution_filename);
		if($add_result !== true) {
			return "Could not add the zip file at $solution_real_path, " . $this->ZipStatusString($add_result);
		}
		$add_result = $zip_file->addFile($judging_input_real_path, 'judge.in');
		if($add_result !== true) {
			return "Could not add the zip file at $solution_real_path, " . $this->ZipStatusString($add_result);
		}
		$add_result = $zip_file->addFile($judging_output_real_path, 'judge.out');
		if($add_result !== true) {
			return "Could not add the zip file at $solution_real_path, " . $this->ZipStatusString($add_result);
		}

		// recursively add the judging scripts
		$this->addJudgeScripts($zip_file);

		$close_result = $zip_file->close();
		if($close_result !== true) {
			return "Could not close the zip file at $zip_path, " . $this->ZipStatusString($close_result);
		}

		// download the zip file
		return Response::download($zip_path);
	}

	private function ZipStatusString( $status )
	{
		switch( (int) $status )
		{
			case ZipArchive::ER_OK           : return 'N No error';
			case ZipArchive::ER_MULTIDISK    : return 'N Multi-disk zip archives not supported';
			case ZipArchive::ER_RENAME       : return 'S Renaming temporary file failed';
			case ZipArchive::ER_CLOSE        : return 'S Closing zip archive failed';
			case ZipArchive::ER_SEEK         : return 'S Seek error';
			case ZipArchive::ER_READ         : return 'S Read error';
			case ZipArchive::ER_WRITE        : return 'S Write error';
			case ZipArchive::ER_CRC          : return 'N CRC error';
			case ZipArchive::ER_ZIPCLOSED    : return 'N Containing zip archive was closed';
			case ZipArchive::ER_NOENT        : return 'N No such file';
			case ZipArchive::ER_EXISTS       : return 'N File already exists';
			case ZipArchive::ER_OPEN         : return 'S Can\'t open file';
			case ZipArchive::ER_TMPOPEN      : return 'S Failure to create temporary file';
			case ZipArchive::ER_ZLIB         : return 'Z Zlib error';
			case ZipArchive::ER_MEMORY       : return 'N Malloc failure';
			case ZipArchive::ER_CHANGED      : return 'N Entry has been changed';
			case ZipArchive::ER_COMPNOTSUPP  : return 'N Compression method not supported';
			case ZipArchive::ER_EOF          : return 'N Premature EOF';
			case ZipArchive::ER_INVAL        : return 'N Invalid argument';
			case ZipArchive::ER_NOZIP        : return 'N Not a zip archive';
			case ZipArchive::ER_INTERNAL     : return 'N Internal error';
			case ZipArchive::ER_INCONS       : return 'N Zip archive inconsistent';
			case ZipArchive::ER_REMOVE       : return 'S Can\'t remove file';
			case ZipArchive::ER_DELETED      : return 'N Entry has been deleted';

			default: return sprintf('Unknown status %s', $status );
		}
	}

	/**
	 * Adds the correct directory structure for the judge scripts. In the future,
	 * the judge will not have to download this everytime, but instead run it as
	 * an installed script, which will dynamically pull down from the serve.
	 */
	private function addJudgeScripts($zip_file) {
		// the judge scripts' root directory
		$root_path = app_path() . "/library/scripts/";

		// add the main script
		$zip_file->addFile("$root_path/judge", "judge");

		// add the folder structure
		$zip_file->addEmptyDir('lib');
		$zip_file->addEmptyDir('lib/languages');

		// add lib files
		foreach(glob($root_path . "/lib/*.py") as $full_path) {
			$zip_file->addFile($full_path, 'lib/' . basename($full_path));
		}

		// add language judgers
		foreach(glob($root_path . "/lib/languages/*.py") as $full_path) {
			$zip_file->addFile($full_path, 'lib/languages/' . basename($full_path));
		}
	}
}