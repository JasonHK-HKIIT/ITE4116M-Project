<?php

namespace App\Data\Assistant;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class ThreadCreatePayload extends Data
{
    public function __construct(
        public string $name,
        public string $assistantId)
    {}

    public static function fromMultiple(string $name, string $assistantId): self
    {
        return new self($name, $assistantId);
    }
}
