<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Luilliarcec\DevUtilities\DevUtilitiesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\QueryBuilder\QueryBuilder;
use Tests\Utils\User;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Utils/migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [DevUtilitiesServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:JjrFWC+TGnySY2LsldPXAxuHpyjh8UuoPMt6yy2gJ8U=');
        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('utilities.auth_foreign_id_column', 'user_id');

        /** Database */
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);

        /** Filters */
        $app['config']->set('query-builder.parameters', [
            'include' => 'include',
            'filter' => 'filter',
            'sort' => 'sort',
            'fields' => 'fields',
            'append' => 'append',
        ]);
    }

    protected function defineWebRoutes($router)
    {
        $router->post('/form', function (Request $request) {
            $request->validate([
                'first_name' => 'bail|required|string|min:5|max:30',
                'email' => 'unique:users,email'
            ]);
        });

        $router->get('/sorts', function (Request $request) {
            $data = QueryBuilder::for(User::class)
                ->allowedSorts(['name'])
                ->get();

            return $this->list($data, 'name');
        });

        $router->get('/filters', function (Request $request) {
            $data = QueryBuilder::for(User::class)
                ->allowedFilters(['name'])
                ->get();

            return $this->list($data, 'name');
        });
    }

    protected function list(Collection $data, string $field, string $li = ''): string
    {
        foreach ($data as $item) {
            $li .= sprintf('<li>%s</li>', $item->{$field});
        }

        return sprintf('<ul>%s</ul>', $li);
    }
}
