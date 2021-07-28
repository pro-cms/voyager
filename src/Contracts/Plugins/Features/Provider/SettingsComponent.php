<?php

namespace Voyager\Admin\Contracts\Plugins\Features\Provider;

/**
 * @property      string $settings_component        The settings component name.
 */
interface SettingsComponent
{
    public function getSettingsComponent(): string;
}