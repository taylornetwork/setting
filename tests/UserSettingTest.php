<?php

namespace TaylorNetwork\Tests;

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('TaylorNetwork\\Tests\\', __DIR__.'/');

use Illuminate\Support\Facades\Auth;
use Orchestra\Testbench\TestCase;
use TaylorNetwork\Setting\Facades\UserSetting;
use TaylorNetwork\Setting\SettingServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [SettingServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'UserSetting' => UserSetting::class
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
        $this->assertEquals('default', UserSetting::get('unsetKey', 'default'));
    }

    public function testFacadeValue()
    {
        $this->assertEquals('found', UserSetting::get('someKey', 'default'));
    }

    public function testHelperDefault()
    {
        $this->assertEquals('default', user_setting('unsetKey', 'default'));
    }

    public function testHelperValue()
    {
        $this->assertEquals('found', user_setting('someKey', 'default'));
    }

    public function testUserCall()
    {
        $this->assertEquals('found', TestUser::first()->setting('someKey'));
    }

    public function testUserUpdate()
    {
        TestUser::first()->updateSetting('someKey', 'newValue');
        $this->assertEquals('newValue', user_setting('someKey'));
    }

    public function testNonLoginUser()
    {
        $this->assertEquals('newValue', TestUser::find(2)->setting('aKey'));
    }

    public function testFacadeSet()
    {
        UserSetting::set('anotherKey', 'aValue!');
        $this->assertEquals('aValue!', UserSetting::get('anotherKey'));
    }

    public function testBool()
    {
        $this->assertTrue(user_setting('boolTestTrue'));
        $this->assertFalse(user_setting('boolTestFalse'));
    }

    public function testInt()
    {
        $this->assertEquals(55, user_setting('isInt'));
        $this->assertIsInt(user_setting('isInt'));
    }

    public function testString()
    {
        $this->assertEquals('this is a string', user_setting('isString'));
    }
}
