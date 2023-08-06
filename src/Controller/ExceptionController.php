<?php

declare(strict_types=1);

namespace App\Controller;

use App\Framework\Controller\AbstractController;
use App\Framework\Model\App;

final class HomeController extends AbstractController
{
    public function handleRequest(): App
    {
        ob_start();
        var_dump($this->app);
        $dump = ob_get_clean();
        $this->app->view = $dump ?: 'nothing';
        return $this->app;
    }
}
