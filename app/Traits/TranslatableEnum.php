<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait TranslatableEnum
{
    /**
     * Get the translated label for this enum case.
     */
    public function label(?string $locale = null): string
    {
        $locale ??= App::getLocale();
        $enumName = class_basename(self::class);
        $enumType = match ($enumName) {
            'Disciplines' => 'disciplines',
            'Attributes' => 'attributes',
            'ActivityTypes' => 'activity_types',
            default => strtolower($enumName),
        };

        // Try to get from activityEnums with nested language structure
        $translation = config("activityEnums.{$enumType}.{$this->value}.{$locale}");
        
        // Fallback to English if translation not found
        if (!$translation) {
            $translation = config("activityEnums.{$enumType}.{$this->value}.en") ?? $this->value;
        }
        
        return $translation ?? $this->value;
    }

    /**
     * Get all cases with translated labels.
     */
    public static function optionsForCurrentLocale(): array
    {
        return collect(self::cases())
            ->map(fn($case) => ['value' => $case->value, 'label' => $case->label()])
            ->toArray();
    }

    /**
     * Get all cases with translated labels for a specific locale.
     */
    public static function optionsForLocale(string $locale): array
    {
        return collect(self::cases())
            ->map(fn($case) => ['value' => $case->value, 'label' => $case->label($locale)])
            ->toArray();
    }
}
