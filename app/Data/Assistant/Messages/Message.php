<?php

namespace App\Data\Assistant\Messages;

use App\Enums\Assistant\MessageType;
use Livewire\Wireable;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\PropertyForMorph;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Contracts\PropertyMorphableData;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
abstract class Message extends Data implements PropertyMorphableData, Wireable
{
    use WireableData;

    public string|null|Optional $id;

    #[PropertyForMorph]
    public MessageType $type;

    public string|null|Optional $name;

    /** @var string|array<int,string|array> */
    public string|array $content;

    /** @var array<string,mixed> */
    public array $responseMetadata;

    /** @var array<string,mixed> */
    public array $additionalKwargs;

    public static function morph(array $properties): ?string
    {
        return match ($properties['type'])
        {
            MessageType::AI => AIMessage::class,
            MessageType::Human => HumanMessage::class,
            MessageType::System => SystemMessage::class,
            MessageType::Tool => ToolMessage::class,
            default => null,
        };
    }
}
