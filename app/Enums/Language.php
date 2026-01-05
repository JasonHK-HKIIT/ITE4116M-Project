<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum Language: string
{
    case ENGLISH = 'en';
    case CHINESE_TRADITIONAL = 'zh-HK';
    case CHINESE_SIMPLIFIED = 'zh-CN';
    
    use EnumValues;

    public function toLocale(): string
    {
        return str_replace('-', '_', $this->value);
    }

    public static function fromLocale(string $locale): Language
    {
        return Language::from(str_replace('_', '-', $locale));
    }
}
