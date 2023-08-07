<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Controller\HomeController;
use App\Controller\KRJerseyNumbersController;
use App\Framework\Model\App;
use App\Framework\Model\Routing\Route;
use App\Framework\Model\Routing\Router;
use App\Framework\Model\Style\Style;

$app = App::createWithRequestFromGlobals();

$router = Router::create($app)
    ->addRoutes(
        Route::get('/', HomeController::class, 'handleRequest'),
        Route::get(
            '/jerseys',
            KRJerseyNumbersController::class,
            'handleRequest'
        )
    );

try {
    $app = $router->route();
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
            print_r($app, true)
        ); ?></pre>
    </div>
    <footer>
    </footer>
</body>
<script>
</script>

<!-- Variable declarations -->
<style lang="css">
    <?= Style::create(':root', [
                '--color-primary' => '#ff8800',
                '--color-secondary' => '#5dbaff',
                '--color-white' => '#ffffff',
            ]); ?>
</style>

<!-- Actual styles -->
<?php include('views/framework/base-styles.php'); ?>

<!-- App styling -->
<style>
    <?= $app->renderStyle(); ?>
</style>

</html>
