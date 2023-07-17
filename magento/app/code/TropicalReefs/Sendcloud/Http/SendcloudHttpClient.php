<?php

namespace TropicalReefs\Sendcloud\Http;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;

class SendcloudHttpClient
{
    protected ClientInterface|Client $client;

    public function __construct(
        ?array $options = []
    )
    {
        $this->client = new Client(
            array_replace($this->getDefaultClientOptions(), $options ?? [])
        );
    }

    /**
     * @param MessageInterface $request
     * @param array $options
     * @return ResponseInterface
     */
    public function send(MessageInterface $request, array $options = []): ResponseInterface
    {
        try {
            return $this->client->send($request, $options);
        } catch (GuzzleException $e) {
            return $e->getResponse();
        }
    }

    /**
     * @param string $uri
     * @return MessageInterface
     */
    public function get(string $uri): MessageInterface
    {
        return new Request('GET', $uri);
    }

    /**
     * @param string $uri
     * @param array|null $body
     * @return MessageInterface
     * @throws JsonException
     */
    public function post(string $uri, ?array $body = null): MessageInterface
    {
        return (new Request('POST', $uri))->withBody(Utils::streamFor(json_encode($body, JSON_THROW_ON_ERROR)));
    }

    /**
     * @param string $uri
     * @param null|array $body
     * @return MessageInterface
     * @throws JsonException
     */
    public function put(string $uri, ?array $body = null): MessageInterface
    {
        return (new Request('PUT', $uri))->withBody(Utils::streamFor(json_encode($body, JSON_THROW_ON_ERROR)));
    }

    /**
     * @param string $uri
     * @param array|string|null $body
     * @return MessageInterface
     * @throws JsonException
     */
    public function patch(string $uri, array|string $body = null): MessageInterface
    {
        if (is_array($body)) {
            $body = json_encode($body, JSON_THROW_ON_ERROR);
        }
        return (new Request('PATCH', $uri))->withBody(Utils::streamFor($body));
    }

    /**
     * @param string $uri
     * @param null|array $body
     * @return MessageInterface
     * @throws JsonException
     */
    public function delete(string $uri, ?array $body = null): MessageInterface
    {
        return (new Request('DELETE', $uri))->withBody(Utils::streamFor(json_encode($body, JSON_THROW_ON_ERROR)));
    }

    /**
     * @return string[]
     */
    protected function getDefaultClientOptions(): array
    {
        return [

        ];
    }
}
