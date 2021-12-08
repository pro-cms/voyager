<?php

namespace Voyager\Admin\Plugins;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Voyager\Admin\Classes\UserMenuItem;
use Voyager\Admin\Contracts\Plugins\AuthenticationPlugin as AuthContract;
use Voyager\Admin\Contracts\Plugins\ThemePlugin as ThemeContract;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;
use Voyager\Admin\Manager\Menu as MenuManager;

class AuthenticationPlugin implements AuthContract, ThemeContract
{
    private bool $registered = false;

    public function user(): ?Authenticatable
    {
        return Auth::user();
    }

    public function provideCSS(): string
    {
        return '';
    }

    public function name(): ?string
    {
        return Auth::user()?->{$this->nameField()}; // @phpstan-ignore-line
    }

    public function nameField(): string
    {
        return 'name';
    }

    public function avatar(): ?string
    {
        return VoyagerFacade::assetUrl('images/default-avatar.png');
    }

    public function guard(): string
    {
        return 'web';
    }

    public function authenticate(Request $request): ?array
    {
        if (!$request->get('email', null) || !$request->get('password', null)) {
            return [ __('voyager::auth.error_field_empty') ];
        }

        // TODO: Throttle attempts
        if (Auth::attempt($request->only('email', 'password'), $request->has('remember'))) {
            return null;
        }

        return [ __('voyager::auth.login_failed') ];
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        return redirect()->route('voyager.login');
    }

    public function redirectTo(): string
    {
        return route('voyager.dashboard');
    }

    public function forgotPassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        // TODO: Throttle attempts
        $email = $request->get('email');
        // TODO: Validate Email, check if it exists, send mail

        return redirect()->back()->with([
            'success' => __('voyager::auth.forgot_password_conf'),
        ]);
    }

    public function handleRequest(Request $request, Closure $next): mixed
    {
        if (!$this->registered) {
            auth()->setDefaultDriver($this->guard());
            $this->registered = true;
            Event::dispatch('voyager.auth.registered', $this);

            $this->registerUserMenuItems();
        }

        if ($this->user() && !Auth::guest() && VoyagerFacade::authorize($this->user(), 'browse', ['voyager'])) {
            return $next($request);
        }

        return redirect()->guest(route('voyager.login'));
    }

    public function loginComponent(): ?string
    {
        return null; // Return null will show the default form
    }

    public function forgotPasswordView(): bool
    {
        return true;
    }

    private function registerUserMenuItems() {
        app(MenuManager::class)->addItems(
            (new UserMenuItem(__('voyager::generic.dashboard')))->route('voyager.dashboard'),
            (new UserMenuItem(__('voyager::auth.logout')))->route('voyager.logout')
        );
    }
}
