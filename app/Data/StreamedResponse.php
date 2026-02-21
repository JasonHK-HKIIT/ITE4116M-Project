<?php

namespace App\Data;

use Spatie\LaravelData\Data;
use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Contracts\HttpClient\ResponseInterface;

class StreamedResponse extends Data
{
    public function __construct(
        public EventSourceHttpClient $client,
        public ResponseInterface $source)
    {}
}
