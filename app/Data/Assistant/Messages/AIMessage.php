<?php

namespace App\Data\Assistant\Messages;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class AIMessage extends Message
{
    /** @var ToolCall[] */
    public array $toolCalls;

    /** @var InvalidToolCall[] */
    public array $invalidToolCalls;
}
