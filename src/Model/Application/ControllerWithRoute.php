<?php

declare(strict_types=1);

namespace App\Model\Application;

use App\Controller\AbstractController;
use App\Model\Application\Attribute\RouteAttribute;

final class ControllerWithRoute
{
    public function __construct(
        public readonly AbstractController $controller,
        public readonly RouteAttribute $routeAttribute
    ) {
    }
}
