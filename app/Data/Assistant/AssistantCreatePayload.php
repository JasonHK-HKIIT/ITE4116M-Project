<?php

namespace App\Data\Assistant;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class AssistantCreatePayload extends Data
{
    public string $name;

    public array $config;

    public bool|null|Optional $public;
}
