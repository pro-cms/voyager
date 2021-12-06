<?php

namespace Voyager\Admin\Contracts\Plugins;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\View\View;

interface AuthenticationPlugin extends GenericPlugin
{
    public function user(): ?Authenticatable;

    public function name(): ?string;

    public function nameField(): string;

    public function avatar(): ?string;

    public function guard(): string;

    public function authenticate(Request $request): ?array;

    public function logout(): \Illuminate\Http\RedirectResponse;

    public function redirectTo(): string;

    public function forgotPassword(Request $request): \Illuminate\Http\RedirectResponse;

    public function handleRequest(Request $request, Closure $next): mixed;

    public function loginComponent(): ?string;

    public function forgotPasswordView(): bool;
}
