<?php

namespace App\Data\Assistant\Messages;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
class InvalidToolCall extends Data
{
    public string|null $id;

    public string|null $name;

    public string|null $args;

    public string|null $error;

    public int|string|Optional $index;

    /** @var array<string,mixed>|Optional */
    public array|Optional $extras;
}
