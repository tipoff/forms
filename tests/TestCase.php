<?php

declare(strict_types=1);

namespace Tipoff\Forms\Tests;

use DrewRoberts\Blog\BlogServiceProvider;
use DrewRoberts\Media\MediaServiceProvider;
use Laravel\Nova\NovaCoreServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Tipoff\Addresses\AddressesServiceProvider;
use Tipoff\Authorization\AuthorizationServiceProvider;
use Tipoff\Forms\FormsServiceProvider;
use Tipoff\Notes\NotesServiceProvider;
use Tipoff\Locations\LocationsServiceProvider;
use Tipoff\Statuses\StatusesServiceProvider;
use Tipoff\Support\SupportServiceProvider;
use Tipoff\TestSupport\BaseTestCase;
use Tipoff\TestSupport\Providers\NovaPackageServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            NovaCoreServiceProvider::class,
            NovaPackageServiceProvider::class,
            SupportServiceProvider::class,
            PermissionServiceProvider::class,
            AuthorizationServiceProvider::class,
            StatusesServiceProvider::class,
            NotesServiceProvider::class,
            AddressesServiceProvider::class,
            MediaServiceProvider::class,
            SeoServiceProvider::class,
            BlogServiceProvider::class,
            LocationsServiceProvider::class,
            FormsServiceProvider::class,
        ];
    }
}
