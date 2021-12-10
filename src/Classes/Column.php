<?php

namespace Voyager\Admin\Classes;

/**
 * Dynamically casted to strings by Translatable Trait
 * 
 * @property string $slug
 * @property string $name_singular
 * @property string $name_plural
 */

class Column {
    public function __construct(public string $type, public string $column) {}
}