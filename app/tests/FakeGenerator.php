<?php

namespace Tests;

use Faker\Generator as FakerGenerator;

/**
 * Trait FakeGenerator.
 */
trait FakeGenerator
{
    /**
     * Get faker instance.
     *
     * @return FakerGenerator
     */
    protected function getFake(): FakerGenerator
    {
        return $this->app->make(FakerGenerator::class);
    }
}
