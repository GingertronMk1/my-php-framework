<?php

declare(strict_types=1);

namespace App\Framework\Exception;

use Exception;

final class StyleException extends Exception
{
    public static function nullSelector(string $originalSelector): self
    {
        return new self(
            "Selector is null. Original selector was: `{$originalSelector}`."
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
