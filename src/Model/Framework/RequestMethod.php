<?php

declare(strict_types=1);

namespace App\Model\Framework;

use Exception;

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
        foreach (self::cases() as $case) {
            if ($method === $case->value) {
                return $case;
            }
        }
        throw new Exception("{$method} is not supported", 405);
    }
}
