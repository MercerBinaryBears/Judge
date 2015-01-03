<?php

interface SolutionStateRepository {
    public function firstPendingId();
    public function firstCorrectId();
    public function all();
}
