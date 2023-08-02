<?php

declare(strict_types=1);

namespace App\Model\Framework;

use Exception;

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
            throw new Exception('No request value');
        }
        return new self(
            $_ENV['REQUEST_URI'],
            RequestMethod::getFromString($_ENV['REQUEST_METHOD']),
            $_REQUEST
        );
    }
}
