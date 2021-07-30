<?php

namespace Voyager\Admin\Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
use Voyager\Admin\Manager\Settings as SettingsManager;

class SettingsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Auth::loginUsingId(1);
    }

    public function test_can_get_settings_group()
    {
        $settings = VoyagerFacade::setting('admin')->toArray();
        $this->assertTrue(
            is_array($settings) && count($settings) > 0
        );
    }

    public function test_can_get_setting()
    {
        $setting = VoyagerFacade::setting('admin.title');
        $this->assertEquals($setting, 'Voyager II');
    }

    public function test_can_enable_dev_server()
    {
        resolve(SettingsManager::class)->set('admin.dev-server', true);

        $this->assertEquals(VoyagerFacade::setting('admin.dev-server'), true);

        // TODO: Check this
        /*$this->get(route('voyager.settings.index'))
        ->assertViewHas([
            'devServerWanted'       => true,
            'devServerAvailable'    => false,
            'devServerUrl'          => 'http://localhost:8081/',
        ]);*/
    }

    public function test_can_disable_dev_server()
    {
        $this->post(route('voyager.disable-dev-server'))->assertStatus(200);

        $this->assertEquals(VoyagerFacade::setting('admin.dev-server'), false);

        $this->get(route('voyager.settings.index'))
        ->assertViewHas([
            'devServerWanted'       => false,
            'devServerAvailable'    => false,
            'devServerUrl'          => null,
        ]);
    }
}
