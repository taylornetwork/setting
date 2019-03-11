<?php

namespace TaylorNetwork\Tests;

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('TaylorNetwork\\Tests\\', __DIR__.'/');

use Illuminate\Support\Facades\Auth;
use Orchestra\Testbench\TestCase;
use TaylorNetwork\Setting\Facades\Setting;
use TaylorNetwork\Setting\SettingServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [SettingServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Setting' => Setting::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('auth.providers.users.model', TestUser::class);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', __DIR__.'/database/database.sqlite');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        TestUser::create([
            'id' => 1,
            'name' => 'Test User 1',
            'email' => 'testuser1@example.com',
        ])->settings()->create([
            'key' => 'someKey',
            'value' => 'found',
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

    public function testFacadeDefault()
    {
        $this->assertEquals('default', Setting::get('unsetKey', 'default'));
    }

    public function testFacadeValue()
    {
        $this->assertEquals('found', Setting::get('someKey', 'default'));
    }

    public function testHelperDefault()
    {
        $this->assertEquals('default', setting('unsetKey', 'default'));
    }

    public function testHelperValue()
    {
        $this->assertEquals('found', setting('someKey', 'default'));
    }

    public function testUserCall()
    {
        $this->assertEquals('found', TestUser::first()->setting('someKey'));
    }

    public function testUserUpdate()
    {
        TestUser::first()->updateSetting('someKey', 'newValue');
        $this->assertEquals('newValue', setting('someKey'));
    }

    public function testNonLoginUser()
    {
        $this->assertEquals('newValue', TestUser::find(2)->setting('aKey'));
    }

    public function testFacadeSet()
    {
        Setting::set('anotherKey', 'aValue!');
        $this->assertEquals('aValue!', Setting::get('anotherKey'));
    }

}
