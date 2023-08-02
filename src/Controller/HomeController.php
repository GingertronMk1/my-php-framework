<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Application\App;

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
