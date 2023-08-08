<?php

declare(strict_types=1);

namespace App\Framework\Model;

use App\Framework\Exception\RequestException;

enum RequestMethod: string
{
    case CONNECT = 'CONNECT';
    case DELETE = 'DELETE';
    case GET = 'GET';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';
    case POST = 'POST';
    case PUT = 'PUT';
    case TRACE = 'TRACE';

    public static function getFromString(string $method): self
    {
        $caseValues = [];
        foreach (self::cases() as $case) {
            if ($method === $case->value) {
                return $case;
            }
            $caseValues[] = $case->value;
        }
        throw RequestException::invalidMethod($method, $caseValues);
    }
}
