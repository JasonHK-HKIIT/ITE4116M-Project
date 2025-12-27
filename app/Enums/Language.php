<?php

namespace App\Enums;

use App\Traits\EnumValues;

enum Language: string
{
    case en = 'en';
    case zh_HK = 'zh-HK';
    case zh_CN = 'zh-CN';
    
    use EnumValues;
}
