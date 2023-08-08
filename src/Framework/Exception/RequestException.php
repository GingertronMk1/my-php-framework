<?php

declare(strict_types=1);

namespace App\Framework\Exception;

use Exception;

final class RequestException extends Exception
{
    public static function noRequest(): self
    {
        return new self('No request value');
    }

    /**
     * @param array<string> $valid
     */
    public static function invalidMethod(string $method, array $valid): self
    {
        $validString = implode(', ', $valid);
        return new self(
            "{$method} is not supported. Try one of `{$validString}`.",
            405
        );
    }
}
