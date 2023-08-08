<?php

declare(strict_types=1);

namespace App\Framework\Exception;

use Exception;

final class AppException extends Exception
{
    public static function fileDoesNotExist(string $fileName): self
    {
        return new self("File `{$fileName}` does not exist");
    }

    public static function invalidJSON(string $fileName): self
    {
        return new self("Invalid JSON in file `{$fileName}`");
    }
}
