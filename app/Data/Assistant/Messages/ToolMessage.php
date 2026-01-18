<?php

namespace App\Data\Assistant\Messages;

use App\Enums\Assistant\ToolMessageStatus;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class ToolMessage extends Message
{
    public string $toolCallId;

    public ToolMessageStatus $status;

    /** @var mixed|null|Optional */
    public mixed $artifact;
}
