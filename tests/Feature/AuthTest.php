<?php

namespace Voyager\Admin\Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Voyager\Admin\Tests\Unit\TestCase;

class AuthTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_logout()
    {
        Auth::loginUsingId(1);
        $this->get(route('voyager.logout'))
             ->assertRedirect(route('voyager.login'));
    }

    public function test_can_login()
    {
        $this->get(route('voyager.login'))
             ->assertSeeText('Sign in to your account');
    }

    public function test_get_redirected_when_logged_in()
    {
        Auth::loginUsingId(1);
        $this->get(route('voyager.login'))
             ->assertRedirect(route('voyager.dashboard'));
    }

    public function test_can_recover_password()
    {
        $this->post(route('voyager.forgot_password'))
            ->assertStatus(302)
            ->assertSessionHas('success', __('voyager::auth.forgot_password_conf'));

        // TODO: Add more expectations here
    }
}
