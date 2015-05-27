<?php

$factory('Judge\Models\Contest', 'contest', [
    'name' => $faker->sentence(2),
    'starts_at' => Carbon\Carbon::now()->subDay(),
    'ends_at' => Carbon\Carbon::now()->addDay()
]);
