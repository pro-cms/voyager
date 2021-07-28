<?php

namespace Voyager\Admin\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;

    public function __call(string $name, array $arguments): bool
    {
        return true;
    }
}
