<?php

namespace App\Data\Assistant;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class ThreadCreatePayload extends Data
{
    public string $name;

    public string $assistantId;
}
