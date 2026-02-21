<?php

namespace App\Data\Assistant;

use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class AssistantCreatePayload extends Data
{
    /**
     * @param array<string,mixed> $config
     */
    public function __construct(
        public string $name,
        public array $config,
        public bool|null|Optional $public)
    {}

    public static function fromMultiple(string $name, array $config): self
    {
        return new self($name, $config, Optional::create());
    }

    public static function fromMultipleWithPublic(string $name, array $config, bool|null|Optional $public): self
    {
        return new self($name, $config, $public);
    }
}
