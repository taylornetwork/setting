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

    public function testBool()
    {
        $this->assertTrue(setting('boolTestTrue'));
        $this->assertFalse(setting('boolTestFalse'));
    }

    public function testInt()
    {
        $this->assertEquals(55, setting('isInt'));
        $this->assertIsInt(setting('isInt'));
    }

    public function testString()
    {
        $this->assertEquals('this is a string', setting('isString'));
    }
}
