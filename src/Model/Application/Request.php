<?php

declare(strict_types=1);

namespace App\Model\Application;

use Exception;

final class Request
{
    /**
     * @param array<string, mixed> $body
     */
    public function __construct(
        public readonly string $uri,
        public readonly RequestMethod $method,
        public readonly array $body
    ) {
    }

    public static function createFromGlobals(): self
    {
        if (! (isset($_ENV['REQUEST_URI']) || isset($_ENV['REQUEST_METHOD']))) {
            throw new Exception('No request value');
        }
        return new self(
            $_ENV['REQUEST_URI'],
            RequestMethod::getFromString($_ENV['REQUEST_METHOD']),
            $_REQUEST
        );
    }
}
