<?php

declare(strict_types=1);

namespace App\Model\Application\Attribute;

use Attribute;

#[Attribute]
class RouteAttribute
{
    /**
     * @var array<string>
     */
    public readonly array $methods;

    /**
     * @param string|array<string> $methods
     */
    public function __construct(
        public readonly string $path,
        string|array $methods = 'GET',
        public readonly bool $debugOnly = false
    ) {
        if (! is_array($methods)) {
            $methods = [$methods];
        }
        $this->methods = $methods;
    }
}
