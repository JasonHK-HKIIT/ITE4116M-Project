<?php

namespace App\Data\Assistant;

use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class Assistant extends Data
{
    public string $assistantId;
    public string $name;
    public DateTime $updatedAt;
}
