<?php

declare(strict_types=1);

namespace App\Framework\Model;

use App\Framework\Exception\RequestException;

final readonly class Request
{
    /**
     * @param array<string, mixed> $body
     */
    public function __construct(
        public string $uri,
        public RequestMethod $method,
        public array $body
    ) {
    }

    public static function createFromGlobals(): self
    {
        if (! (isset($_ENV['REQUEST_URI']) || isset($_ENV['REQUEST_METHOD']))) {
            throw RequestException::noRequest();
        }
        return new self(
            $_ENV['REQUEST_URI'],
            RequestMethod::getFromString($_ENV['REQUEST_METHOD']),
            $_REQUEST
        );
    }
}
