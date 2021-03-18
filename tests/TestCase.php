<?php

namespace Milebits\Authoriser\Tests;

use Illuminate\Foundation\Application;
use Milebits\Authorizer\Providers\AuthorizerServiceProvider;
use Orchestra\Testbench\TestCase as MasterTest;

/**
 * Class TestCase
 * @package Milebits\Authoriser\Tests
 */
class TestCase extends MasterTest
{
    /**
     * Setup the application
     *
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param Application $app
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            AuthorizerServiceProvider::class
        ];
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {

    }
}
