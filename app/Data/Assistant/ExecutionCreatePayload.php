<?php

namespace App\Data\Assistant;

use App\Data\Assistant\Messages\Message;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapOutputName(SnakeCaseMapper::class)]
class ExecutionCreatePayload extends Data
{
    /**
     * @param Message[]|array<string,mixed>|null|Optional $input
     * @param array<string,mixed>|null|Optional $config
     */
    public function __construct(
        public string $threadId,
        public array|null|Optional $input,
        public array|null|Optional $config)
    {}

    public static function fromThreadId(string $threadId): self
    {
        return new self($threadId, Optional::create(), Optional::create());
    }

    /**
     * @param Message[]|array<string,mixed> $input
     */
    public static function fromThreadIdAndInput(string $threadId, array $input): self
    {
        return new self($threadId, $input, Optional::create());
    }

    /**
     * @param array<string,mixed> $config
     */
    public static function fromThreadIdAndConfig(string $threadId, array $config): self
    {
        return new self($threadId, Optional::create(), $config);
    }
    
    /**
     * @param Message[]|array<string,mixed> $input
     * @param array<string,mixed> $config
     */
    public static function fromThreadIdAndInputAndConfig(string $threadId, array $input, array $config): self
    {
        return new self($threadId, $input, $config);
    }
}
