<?php

namespace Voyager\Admin\Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Voyager\Admin\Facades\Voyager;
use Voyager\Admin\Tests\Unit\TestCase;

class MiscTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Auth::loginUsingId(1);
    }

    public function test_can_get_disks()
    {
        // TODO: Validate JSON
        $res = $this->post(route('voyager.get-disks'))->assertStatus(200);
    }

    public function test_can_get_thumbnail_options()
    {
        // TODO: Validate JSON
        $res = $this->post(route('voyager.get-thumbnail-options'))->assertStatus(200);
        $res = $this->post(route('voyager.get-thumbnail-options'), ['method' => 'fit'])->assertStatus(200);
        $res = $this->post(route('voyager.get-thumbnail-options'), ['method' => 'crop'])->assertStatus(200);
        $res = $this->post(route('voyager.get-thumbnail-options'), ['method' => 'resize'])->assertStatus(200);
    }

    public function test_can_get_watermark_options()
    {
        // TODO: Validate JSON
        $res = $this->post(route('voyager.get-watermark-options'))->assertStatus(200);
    }

    public function test_can_access_ui_page()
    {
        $res = $this->get(route('voyager.ui'))->assertStatus(200);
    }
}