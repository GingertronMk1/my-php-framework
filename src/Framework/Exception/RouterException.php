<?php

declare(strict_types=1);

namespace App\Framework\Exception;

use App\Framework\Model\RequestMethod;
use Exception;

final class RouterException extends Exception
{
    public static function pathAndMethodTaken(
        string $path,
        RequestMethod $method
    ): self {
        return new self(
            "Path {$path} with method {$method->value} is already taken"
        );
    }

    public static function nameTaken(string $name): self
    {
        return new self("Route with name `{$name}` already exists");
    }

    public static function noRouteFoundWithName(string $name): self
    {
        return new self("No route found with name `{$name}`.");
    }
}
