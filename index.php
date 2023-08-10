<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Controller\HomeController;
use App\Controller\KRJerseyNumbersController;
use App\Framework\Model\App;
use App\Framework\Model\Routing\Route;
use App\Framework\Model\Routing\Router;
use App\Framework\Model\Style\Style;

$router = new Router([
    Route::get('/', HomeController::class, 'handleRequest', 'index'),
    Route::get(
        '/jerseys',
        KRJerseyNumbersController::class,
        'handleRequest',
        'jerseys'
    ),
]);

$app = App::createWithRequestFromGlobals($router);
try {
    $app = $app->route();
} catch (Exception $e) {
    $app = App::fromException($e);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= $app->pageTitle; ?></title>
    <meta name="author" content="">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <header class="header">
        <h1><?= $app->pageTitle; ?></h1>
        <div class="header__links">
        <a href="<?= $app->router->getRouteFromName(
            'index'
        )->path; ?>">Index</a>
        <a href="<?= $app->router->getRouteFromName(
            'jerseys'
        )->path; ?>">Jerseys</a>
        </div>
    </header>
    <div class="body">
        <?= $app->view; ?>
    </div>
    <div class="debug-footer">
        <input type="checkbox" id="debug-footer__checkbox" name="debug-checkbox">
        <label for="debug-footer__checkbox" id="debug-footer__checkbox-label">
            Show debug info?
        </label>
        <pre id="debug-footer__value"><?= htmlspecialchars(
            var_export($app, true)
        ); ?></pre>
    </div>
    <footer>
    </footer>
</body>
<script>
</script>

<!-- Base styles -->
<?= $app->printBaseStyles(); ?>

<!-- App styling -->
<?= $app->printStyle(); ?>

</html>
