<?php

namespace App\Data\Assistant;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class ThreadUpdatePayload extends Data
{
    public array $values;

    /** @var array<string,mixed>|null|Optional */
    public array|null|Optional $config;
}
