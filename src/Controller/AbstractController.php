<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Application\App;

abstract class AbstractController
{
    public function __construct(
        public App $app
    ) {
    }

    public function handleRequest(): App
    {
        return $this->app;
    }
}
