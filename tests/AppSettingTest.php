<?php

namespace TaylorNetwork\Tests;

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4('TaylorNetwork\\Tests\\', __DIR__.'/');

use Orchestra\Testbench\TestCase;
use TaylorNetwork\Setting\Facades\AppSetting;
use TaylorNetwork\Setting\SettingServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [SettingServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'AppSetting' => AppSetting::class
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

        AppSetting::create([
            'key' => 'someKey',
            'value' => 'found',
        ]);

        AppSetting::create([
            'key' => 'boolTestTrue',
            'value' => true,
        ]);

        AppSetting::create([
            'key' => 'boolTestFalse',
            'value' => false,
        ]);

        AppSetting::create([
            'key' => 'isInt',
            'value' => 55,
        ]);

        AppSetting::create([
            'key' => 'isString',
            'value' => 'this is a string',
        ]);

    }

    public function testFacadeDefault()
    {
        $this->assertEquals('defaultValue', AppSetting::get('someUnsetKey', 'defaultValue'));
    }

    public function testFacadeFound()
    {
        $this->assertEquals('found', AppSetting::get('someKey', 'default'));
    }

    public function testHelper()
    {
        $this->assertEquals('found', app_setting('someKey', 'default'));
    }

    public function testBool()
    {
        $this->assertTrue(AppSetting::get('boolTestTrue'));
        $this->assertFalse(app_setting('boolTestFalse'));
    }

    public function testInt()
    {
        $this->assertIsInt(AppSetting::get('isInt'));
    }

    public function testString()
    {
        $this->assertIsString(AppSetting::get('isString'));
    }

    public function testNull()
    {
        $this->assertEmpty(app_setting('unsetKey'));
    }
}