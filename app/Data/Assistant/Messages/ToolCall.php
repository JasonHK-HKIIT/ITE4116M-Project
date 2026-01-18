<?php

namespace App\Data\Assistant\Messages;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class ToolCall extends Data
{
    public string|null|Optional $id;

    public string $name;

    /** @var array<string,mixed> */
    public array $args;
}
