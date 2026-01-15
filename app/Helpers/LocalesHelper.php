<?php

namespace App\Helpers;

use Astrotomic\Translatable\Locales;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;

class LocalesHelper
{
    public static function locales(): array
    {
        return ['en', 'zh-HK', 'zh-CN'];
    }

    /**
     * Build multi-locale validation rules for validating a localised field.
     */
    public static function buildRules(string $field, array|string $rules): array
    {
        return collect(LocalesHelper::locales())
            ->flatMap(fn($locale, $key) => ["$field.$locale" => $rules])
            ->toArray();
    }

    /**
     * Build multi-locale validation attributes for validating a localised field.
     */
    public static function buildValidationAttributes(string $field, mixed $attributes = null): array
    {
        return collect(LocalesHelper::locales())
            ->flatMap(fn($locale, $key) => ["$field.$locale" => ($attributes ?? $field)])
            ->toArray();
    }

    /**
     * Build multi-locale property value for validating a localised field.
     */
    public static function buildPropertyValue(mixed $value = null): array
    {
        return collect(LocalesHelper::locales())
            ->flatMap(fn($locale, $key) => [$locale => $value])
            ->toArray();
    }

    public static function transformToModelFields(Arrayable|iterable $map, Enumerable|array|string $fields, bool $keepUntransformed = true): array
    {
        $languages = collect($map)
            ->only($fields)
            ->filter(fn($v) => is_array($v))
            ->flatMap(fn($v) => array_keys($v))
            ->unique()
            ->values();
        
        $transformed = $languages->mapWithKeys(function ($lang) use ($map, $fields)
        {
            return [
                $lang => collect($map)
                    ->only($fields)
                    ->filter(fn($v) => is_array($v))
                    ->map(fn($translations) => $translations[$lang] ?? null)
                    ->filter()
                    ->toArray()
            ];
        });
        
        return collect($map)->except($fields)->merge($transformed)->toArray();
    }

    public static function transformToProperties(Arrayable|iterable $map, bool $keepUntransformed = true): Collection
    {
        $locales = LocalesHelper::locales();

        $transformed = collect($map)
            ->only($locales)
            ->filter(fn($value) => is_array($value))
            ->map(function ($fields, $locale)
            {
                return collect($fields)
                    ->map(fn($value, $field) =>
                    [
                        'field' => $field,
                        'locale' => $locale,
                        'value' => $value,
                    ])
                    ->values();
            })
            ->flatten(1)
            ->groupBy('field')
            ->map(fn($items) => $items->pluck('value', 'locale')->toArray());

        return ($keepUntransformed ? collect($map)->except($locales)->merge($transformed) : $transformed);
    }
}
