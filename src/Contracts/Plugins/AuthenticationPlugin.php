<?php

namespace Voyager\Admin\Contracts\Plugins;

use Closure;
use Illuminate\Http\Request;
use Illuminate\View\View;

interface AuthenticationPlugin extends GenericPlugin
{
    public function user(): ?object;

    public function name(): ?string;

    public function avatar(): ?string;

    public function guard(): string;

    public function authenticate(Request $request): ?array;

    public function logout(): \Illuminate\Http\RedirectResponse;

    public function redirectTo(): string;

    public function forgotPassword(Request $request): \Illuminate\Http\RedirectResponse;

    public function handleRequest(Request $request, Closure $next): mixed;

    public function loginView(): ?View;

    public function forgotPasswordView(): ?View;
}
