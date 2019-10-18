<?php

namespace Tests\Builder;

use Faker;

class Builder
{
    /**
     * @var Faker\Generator
     */
    protected $faker;

    public function __construct()
    {
        $faker = Faker\Factory::create();
        $faker->seed(1);
        $this->faker = $faker;
    }
}
