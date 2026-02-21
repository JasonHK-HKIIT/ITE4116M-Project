<?php

namespace App\Services;

use App\Data\Assistant\Assistant;
use App\Data\Assistant\AssistantCreatePayload;
use App\Data\Assistant\ExecutionCreatePayload;
use App\Data\Assistant\Thread;
use App\Data\Assistant\ThreadCreatePayload;
use App\Data\Assistant\ThreadState;
use App\Data\StreamedResponse;
use App\Models\User;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AssistantClient
{
    private string $endpoint;
    private User $user;

    public function __construct()
    {
        $this->endpoint = config('app.assistant.base_url');
        $this->user = Auth::user();
    }

    public function isHealthy(): bool
    {
        try
        {
            /** @var \Illuminate\Http\Client\Response */
            $response = $this->getGuzzleClient()->get("{$this->endpoint}/health");
            return $response->successful();
        }
        catch (ConnectionException)
        {
            return false;
        }
    }

    public function createAssistant(AssistantCreatePayload $payload): Assistant
    {
        /** @var \Illuminate\Http\Client\Response */
        $response = $this->getGuzzleClient($this->user->id)->post("{$this->endpoint}/assistants", $payload->toArray());
        return Assistant::from($response->json());
    }

    public function createThread(ThreadCreatePayload $payload): Thread
    {
        /** @var \Illuminate\Http\Client\Response */
        $response = $this->getGuzzleClient($this->user->id)->post("{$this->endpoint}/threads", $payload->toArray());
        return Thread::from($response->json());
    }

    /**
     * @return Thread[]
     */
    public function listThreads(): array
    {
        /** @var \Illuminate\Http\Client\Response */
        $response = $this->getGuzzleClient($this->user->id)->get("{$this->endpoint}/threads/");
        return Thread::collect($response->json(), 'array');
    }

    public function getThread(string $threadId): Thread
    {
        /** @var \Illuminate\Http\Client\Response */
        $response = $this->getGuzzleClient($this->user->id)->get("{$this->endpoint}/threads/{$threadId}");
        return Thread::from($response->json());
    }

    public function getThreadState(string $threadId): ThreadState
    {
        /** @var \Illuminate\Http\Client\Response */
        $response = $this->getGuzzleClient($this->user->id)->get("{$this->endpoint}/threads/{$threadId}/state");
        return ThreadState::from($response->json());
    }

    /**
     * @return [EventSourceHttpClient,ResponseInterface]
     */
    public function streamExecution(ExecutionCreatePayload $payload): StreamedResponse
    {
        $client = new EventSourceHttpClient($this->getSymfonyClient($this->user->id));
        $source = $client->connect("{$this->endpoint}/runs/stream", [ 'json' => $payload->toArray() ], 'POST');
        return new StreamedResponse($client, $source);
    }

    private function getGuzzleClient(?string $userId = null): PendingRequest
    {
        $client = Http::createPendingRequest();
        if (!is_null($userId))
        {
            $client->withHeader('Cookie', sprintf("%s=%s", 'opengpts_user_id', rawurlencode($userId)));
        }

        return $client;
    }

    private function getSymfonyClient(?string $userId = null): HttpClientInterface
    {
        $options = [];
        if (!is_null($userId))
        {
            $options['headers'] = ['Cookie' => sprintf("%s=%s", 'opengpts_user_id', rawurlencode($userId))];
        }

        return HttpClient::create($options);
    }
}
