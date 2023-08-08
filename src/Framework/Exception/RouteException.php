<?php

declare(strict_types=1);

namespace App\Framework\Exception;

use App\Framework\Model\App;
use Exception;

final class RouteException extends Exception
{
    public static function invalidClass(string $className): self
    {
        return new self("{$className} is not a valid class");
    }

    public static function invalidMethod(
        string $className,
        string $methodName
    ): self {
        return new self("{$className}::{$methodName} is not a valid method.");
    }

    public static function invalidMethodType(
        string $className,
        string $methodName,
        string $invalidType
    ): self {
        $appClass = App::class;
        return new self(
            "{$className}::{$methodName} returns `{$invalidType}`, not `{$appClass}`."
        );
    }
}
