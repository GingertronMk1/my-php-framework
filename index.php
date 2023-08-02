<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Controller\HomeController;
use App\Model\Application\App;
use App\Model\Application\Routing\Route;
use App\Model\Application\Routing\Router;

$app = App::createWithRequestFromGlobals();

$router = Router::create($app)
    ->addRoutes(Route::get('/', HomeController::class, 'handleRequest'));

$app = $router->route();

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
        <pre id="debug-footer__value"><?= htmlspecialchars(
            print_r($app, true)
        ); ?></pre>
        <input type="checkbox" id="debug-footer__checkbox" name="debug-checkbox">
        <label for="debug-checkbox" id="debug-footer__checkbox-label">
        Show debug info?
        </label>
    </div>
    <footer>
    </footer>
</body>
<script>
</script>

<!-- Variable declarations -->
<style lang="css">
:root {
    --color-primary: #ff8800;
    --color-secondary: #5dbaff;
    --color-white: #ffffff;
}
</style>

<style lang="css">
  * {
    box-sizing: border-box;
    font-family: sans-serif;
  }

  html,
  body {
    margin: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    background-color: var(--color-secondary);
  }

  <?php foreach(range(1, 6) as $h): ?>
    h<?= $h; ?> {
        margin: 0;
    }
  <?php endforeach; ?>

  .header {
    padding: 1rem;
    display: flex;
    flex-direction: row;
    justify-content: stretch;
    background: linear-gradient(var(--color-primary) 0%, var(--color-white) 250%);
  }

  .body {
    flex: 1;
    width: 95%;
    max-width: 1600px;
    margin: 0 auto;
    padding: 0.5rem;
    display: flex;
    flex-direction: column;
    background-color: var(--color-white);
  }

  table, th, td {
    border-collapse: collapse;
    border: 1px solid black;
  }
  td {
    padding: 0.5rem;
  }

  #debug-footer__value {
    display: none;
    padding: 1rem;
    font-family: monospace;
  }

  #debug-footer__checkbox:checked ~ #debug-footer__value {
    display: block
  }
</style>

</html>
