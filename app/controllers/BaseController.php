<?php

class BaseController extends Controller {

	public function __construct(ContestRepository $contests, LanguageRepository $languages, ProblemRepository $problems) {
		$this->contests = $contests;
		$this->languages = $languages;
		$this->problems = $problems;
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
