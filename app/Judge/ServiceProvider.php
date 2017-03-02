<?php namespace Judge;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function register()
    {
        $contest_repository = $this->app->make('Judge\Repositories\ContestRepository');
        $this->app->singleton('Judge\Repositories\ContestRepository', function () use ($contest_repository) {
            return $contest_repository;
        });

        $problem_repository = new \Judge\Repositories\ProblemRepository($contest_repository);
        $this->app->singleton('Judge\Repositories\ProblemRepository', function () use ($problem_repository) {
            return $problem_repository;
        });

        $solution_state_repository = new \Judge\Repositories\SolutionStateRepository();
        $this->app->singleton('Judge\Repositories\SolutionStateRepository', function () use ($solution_state_repository) {
            return $solution_state_repository;
        });

        $solution_repository = new \Judge\Repositories\SolutionRepository($contest_repository, $problem_repository, $solution_state_repository);
        $this->app->singleton('Judge\Repositories\SolutionRepository', function () use ($solution_repository) {
            return $solution_repository;
        });
    }
}
