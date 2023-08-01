<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Application\App;
use App\Model\Application\Attribute\RouteAttribute;

#[RouteAttribute('/')]
final class HomeController extends AbstractController
{
    public function handleRequest(): App
    {
        $this->app->view = print_r($this, true);
        return $this->app;
    }
}
