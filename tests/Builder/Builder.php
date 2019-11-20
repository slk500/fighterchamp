<?php

namespace Tests\Builder;

class Builder
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function __construct()
    {
        $faker = \Faker\Factory::create();
        $faker->seed(1);
        $this->faker = $faker;
    }
}
