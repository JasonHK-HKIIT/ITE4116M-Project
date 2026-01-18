<?php

namespace App\Data\Assistant\Messages;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class AIMessage extends Message
{
    /** @var ToolCall[] */
    public array $toolCalls;
}
