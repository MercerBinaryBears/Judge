<?php

class BaseController extends Controller {

	/**
	 * Repository for querying contest
	 *
	 * @var $contests
	 */
	protected $contests;

	public function __construct(ContestRepository $contests, LanguageRepository $languages) {
		$this->contests = $contests;
		$this->languages = $languages;
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
