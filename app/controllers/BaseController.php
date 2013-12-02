<?php

class BaseController extends Controller {

	public function __construct(
			ContestRepository $contests, 
			LanguageRepository $languages, 
			ProblemRepository $problems,
			SolutionStateRepository $solution_states
	) {
			
		$this->contests = $contests;
		$this->languages = $languages;
		$this->problems = $problems;
		$this->solution_states = $solution_states;
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
