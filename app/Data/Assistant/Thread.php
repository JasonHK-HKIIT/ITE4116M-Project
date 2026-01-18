<?php

namespace App\Data\Assistant;

use DateTime;
use DateTimeImmutable;
use Livewire\Wireable;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class)]
class Thread extends Data implements Wireable
{
    use WireableData;

    public string $threadId;

    public string $name;

    public string $userId;

    public string|null|Optional $assistantId;

    public array|null|Optional $metadata;

    #[WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i:s.uP')]
    public DateTimeImmutable $updatedAt;
}
