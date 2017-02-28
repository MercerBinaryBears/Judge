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
    }
}
