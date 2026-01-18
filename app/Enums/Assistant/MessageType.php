<?php

namespace App\Enums\Assistant;

enum MessageType: string
{
    case AI = 'ai';
    case Human = 'human';
    case System = 'system';
    case Tool = 'tool';
}
