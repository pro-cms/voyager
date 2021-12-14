<?php

namespace Voyager\Admin\Traits;

use Illuminate\Database\Eloquent\Model;
use Voyager\Admin\Facades\Voyager as VoyagerFacade;

trait Translatable
{
    private bool $translate = true;
    public ?string $current_locale = null;
    public ?string $fallback_locale = null;

    public function setLocales(): void
    {
        if (!$this->current_locale) {
            $this->current_locale = app()->getLocale();
            $this->fallback_locale = config('app.fallback_locale', 'en');
        }
    }

    public function getTranslated(string $key, string $locale, string $fallback, string $default): mixed
    {
        $this->setLocales();

        $old_locale = $this->current_locale;
        $old_fallback = $this->fallback_locale;

        // Set locales to desired
        $this->current_locale = $locale;
        $this->fallback_locale = $fallback;

        $value = $this->__get($key);

        // Set locales back to original
        $this->current_locale = $old_locale;
        $this->fallback_locale = $old_fallback;

        return $value ?? $default;
    }

    public function setTranslated(string $key, mixed $value, string $locale): void
    {
        $old_locale = $this->current_locale;

        // Set locale to desired
        $this->current_locale = $locale;

        $this->__set($key, $value);

        // Set locales back to original
        $this->current_locale = $old_locale;
    }

    public function translate(): void
    {
        $this->translate = true;
    }

    public function dontTranslate(): void
    {
        $this->translate = false;
    }

    /**
     * Retrieve translated model value
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key): mixed
    {
        $this->setLocales();
        $value = null;

        if ($this instanceof Model) {
            $value = $this->getAttribute($key);
        } else {
            $value = $this->{$key};
        }
        if (!$this->translate) {
            return $value;
        }

        if ($this->shouldColumnBeTranslated($key)) {
            return VoyagerFacade::translate($value, $this->current_locale, $this->fallback_locale);
        }

        return $value;
    }

    /**
     *Set translated model value
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value): void
    {
        $this->setLocales();
        if ($this->shouldColumnBeTranslated($key)) {
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            } else {
                $value = json_encode((object) [
                    $this->current_locale => $value,
                ]);
            }
        }
        if ((bool) class_parents($this)) {
            parent::__set($key, $value); // @phpstan-ignore-line
        } else {
            $this->{$key} = $value;
        }
    }

    public function shouldColumnBeTranslated(string $column): bool
    {
        return (
            $this->translate &&
            property_exists($this, 'translatable') &&
            is_array($this->translatable) &&
            in_array($column, $this->translatable)
        );
    }
}
