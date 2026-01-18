<?php

namespace App\Enums\Assistant;

enum ToolMessageStatus: string
{
    case Success = 'success';
    case Error = 'error';
}
