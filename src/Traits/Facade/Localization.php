<?php

namespace Voyager\Admin\Traits\Facade;

use Illuminate\Support\Collection;

trait Localization
{
    protected array $translations = [];
    protected array $locales = [];

    /**
     * Get all Voyager translation strings.
     */
    public function getLocalization(): Collection
    {
        $translator = app()->make('translator');

        return collect(['auth', 'bread', 'builder', 'datetime', 'formfields', 'generic', 'media', 'plugins', 'settings', 'validation'])->flatMap(function ($group) {
            return ['voyager::'.$group => trans('voyager::'.$group)];
        })->merge(collect($this->translations)->flatMap(function ($namespace, $group) use ($translator) {
            $translator->load($namespace, $group, $this->getLocale());
            return [$namespace.'::'.$group => trans($namespace.'::'.$group)];
        }));
    }

    /**
     * Add translations to the Voyager namespace.
     */
    public function addTranslations(string $namespace, string $group): void
    {
        $this->translations[$namespace] = $group;
    }

    /**
     * Get all locales supported by the app.
     */
    public function getLocales(): array
    {
        if (count($this->locales) == 0) {
            return config('app.locales', [$this->getLocale()]);
        }

        return $this->locales;
    }

    /**
     * Add a locale to the supported locales.
     */
    public function addLocale(string $locale): void
    {
        $this->locales[] = $locale;
    }

    /**
     * Set and override all locales.
     */
    public function setLocales(array $locales): void
    {
        $this->locales = $locales;
    }

    /**
     * Get the current app-locale.
     */
    public function getLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Get the app fallback-locale.
     */
    public function getFallbackLocale(): string
    {
        return config('app.fallback_locale', [$this->getLocale()]);
    }

    /**
     * Get if the app is translatable or not.
     */
    public function isTranslatable(): bool
    {
        return count($this->getLocales()) > 1;
    }

    /**
     * Translate a given string/object/array.
     */
    public function translate(mixed $value, ?string $locale = null, ?string $fallback = null): ?string
    {
        if ($locale == null) {
            $locale = app()->getLocale();
        }
        if ($fallback == null) {
            $fallback = config('app.fallback_locale');
        }

        if (is_string($value)) {
            if (($json = $this->getJson($value)) === false) {
                return $value;
            } else {
                $value = $json;
            }
        }

        if (is_array($value)) {
            return $value[$locale] ?? $value[$fallback] ?? null;
        } elseif (is_object($value)) {
            return $value->{$locale} ?? $value->{$fallback} ?? null;
        }

        return $value;
    }

    /**
     * Set a translation in a given string/object/array.
     */
    public function setTranslation(mixed $input, mixed $value, ?string $locale = null): mixed
    {
        if ($locale == null) {
            $locale = app()->getLocale();
        }

        if (is_string($input)) {
            $json = $this->getJson($input);
            if ($json === false) {
                $input = [];
            } else {
                $input = $json;
            }
        }

        if (is_array($input)) {
            $input[$locale] = $value;
        } elseif (is_object($input)) {
            $input->{$locale} = $value;
        }

        return $input;
    }
}