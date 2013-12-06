<?php

interface SolutionStateRepository {
	public function firstPendingId();
	public function all();
}
