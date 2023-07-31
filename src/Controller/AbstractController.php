<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\App;

abstract class AbstractController
{
    public function __construct(
        public readonly App $app
    ) {
    }

    public function handleRequest(): App
    {
        return $this->app;
    }
}
