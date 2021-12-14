<?php

namespace Voyager\Admin\Traits\Facade;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Auth\Authenticatable;

use Voyager\Admin\Contracts\Plugins\{AuthenticationPlugin, AuthorizationPlugin};
use Voyager\Admin\Plugins\AuthenticationPlugin as DefaultAuthPlugin;

trait Auth
{
    /**
     * Gets the authentication plugin.
     */
    public function auth(): AuthenticationPlugin
    {
        return $this->pluginmanager->getAllPlugins()->filter(function ($plugin) {
            return $plugin instanceof AuthenticationPlugin;
        })->first() ?? new DefaultAuthPlugin();
    }

    /**
     * Authorize an action for a user.
     */
    public function authorize(mixed $user, mixed $ability, array $arguments = []): bool
    {
        $authorized = true;
        $this->pluginmanager->getAllPlugins()->filter(function ($plugin) {
            return $plugin instanceof AuthorizationPlugin;
        })->each(function ($plugin) use ($user, $ability, $arguments, &$authorized) {
            if ($plugin->authorize($user, $ability, $arguments) === false) {
                $authorized = false;
            }
        });

        return $authorized;
    }

    /**
     * Resolve a batch of permissions for a given user.
     */
    public function resolvePermissions(array $permissions, ?Authenticatable $user = null): Collection
    {
        if ($user === null) {
            $user = $this->auth()->user();
        }

        return collect($permissions)->mapWithKeys(function ($arguments, $ability) use ($user) {
            if (is_int($ability)) {
                return [$arguments => $this->authorize($user, $arguments)];
            }

            return [$ability => $this->authorize($user, $ability, $arguments)];
        });
    }
}