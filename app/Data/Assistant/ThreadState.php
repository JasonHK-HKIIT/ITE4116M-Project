<?php

namespace App\Data\Assistant;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class ThreadState extends Data
{
    /** @var Messages\Message[] */
    public array $values;
}
