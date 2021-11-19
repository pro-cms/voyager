<?php

namespace Voyager\Admin\Contracts\Plugins;

use Illuminate\View\View;

/**
 * @property-read string  $name                      The name of this voyager plugin.
 * @property-read string  $description               A short description of this plugins function.
 * @property-read string  $repository                The packagist package name for this plugin.
 * @property-read string  $website                   The public URL to this plugins repository.
 * @property      string  $identifier                A string identifying this plugin-class.
 * @property      string  $version                   The composer version of the package.
 * @property      string  $version_normalized        The normalized composer version of the package.
 * @property      bool    $enabled                   Wether the plugin is enabled or not.
 * @property      ?string $readme                    Absolute path to the readme file.
 */
interface GenericPlugin
{
}
