<?php

namespace Voyager\Admin\Contracts\Plugins\Features\Provider;

/**
 * @property      string $instructions_component    The instruction component name.
 */
interface InstructionsComponent
{
    public function getInstructionsComponent(): string;
}