<?php

namespace App\Data\Assistant;

use DateTimeImmutable;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class Assistant extends Data
{
    public string $assistantId;

    public string $name;

    public array $config;

    public string $userId;

    public bool $public;

    #[WithCast(DateTimeInterfaceCast::class)]
    public DateTimeImmutable $updatedAt;
}
