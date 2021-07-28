<?php

namespace Voyager\Admin\Contracts\Plugins;

use Illuminate\Support\Collection;

interface AuthorizationPlugin extends GenericPlugin
{
    public function authorize(mixed $user, string $ability, array $arguments = []): ?bool;
}
