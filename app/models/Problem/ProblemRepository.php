<?php

interface ProblemRepository {
	public function getSelectBoxData();

	public function allIds();

	public function forContest(Contest $c);
}
