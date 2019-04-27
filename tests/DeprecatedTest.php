<?php

namespace TaylorNetwork\Tests;

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('TaylorNetwork\\Tests\\', __DIR__.'/');

use Illuminate\Support\Facades\Auth;
use Orchestra\Testbench\TestCase;
use TaylorNetwork\Setting\Facades\UserSetting;
use TaylorNetwork\Setting\SettingServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeprecatedTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [SettingServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Setting' => UserSetting::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', __DIR__ . '/database/database.sqlite');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $user = TestUser::create([
            'id' => 1,
            'name' => 'Test User 1',
            'email' => 'testuser1@example.com',
        ]);

        $user->settings()->create([
            'key' => 'someKey',
            'value' => 'found',
        ]);

        $user->settings()->create([
            'key' => 'boolTestTrue',
            'value' => true,
        ]);

        $user->settings()->create([
            'key' => 'boolTestFalse',
            'value' => false,
        ]);

        $user->settings()->create([
            'key' => 'isInt',
            'value' => 55,
        ]);

        $user->settings()->create([
            'key' => 'isString',
            'value' => 'this is a string',
        ]);

        TestUser::create([
            'id' => 2,
            'name' => 'Test User 2',
            'email' => 'testuser2@example.com',
        ])->settings()->create([
            'key' => 'aKey',
            'value' => 'newValue',
        ]);

        Auth::login(TestUser::find(1));

    }

    public function testRenamedFacadeDefault()
    {
        $this->assertEquals('defaultValue', \Setting::get('someUnsetKey', 'defaultValue'));
    }

    public function testRenamedFacade()
    {
        $this->assertEquals('found', \Setting::get('someKey', 'default'));
    }

    public function testDeprecatedHelper()
    {
        $this->assertTrue(setting('boolTestTrue'));
    }
}