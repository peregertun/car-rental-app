<?php

use CarRental\Core\Config;
use CarRental\Core\Router;
use CarRental\Core\Request;
use CarRental\Utils\DependencyInjector;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/vendor/autoload.php';

$config = new Config();
$dbConfig = $config->get('database');

$db = new PDO(
    'mysql:host=' . $dbConfig['host'] .
        ';dbname=' . $dbConfig['database'],
    $dbConfig['user'],
    $dbConfig['password']
);

$loader = new Twig\Loader\FilesystemLoader(__DIR__ . '/views');
$view = new Twig\Environment($loader);

$log = new Logger('bookstore');
$logFile = $config->get('log');
$log->pushHandler(new StreamHandler($logFile, Logger::DEBUG));

$di = new DependencyInjector();
$di->set('PDO', $db);
$di->set('Utils\Config', $config);
$di->set('Twig_Environment', $view);
$di->set('Logger', $log);

$header = <<<_END

  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Car rental</title>
      <link rel="stylesheet" href="style.css">
  </head>
  <body>
      
  <div class="container">
      <div class="main">    
  
      <div class="buttons">

      <form method="post" action="/">
          <input type="submit" value="Home" class="submit">
      </form>

      <form action="customers" method="post">
          <input type="submit" value="Customers" class="submit">
      </form>

      <form action="listAll" method="post">
          <input type="submit" value="Cars" class="submit" disabled>
      </form>

      <form action="checkout_car.php" method="post">
          <input type="submit" value="Rent" class="submit" disabled>
      </form>

      <form action="checkin_car.php" method="post">
          <input type="submit" value="Return" class="submit" disabled>
      </form>

      <form action="test" method="post">
          <input type="submit" value="History" class="submit">
      </form>

</div>
_END;

$footer = <<<_END
</div>
</div>
</div>

<script type="text/javascript" src="script.js"></script>

</body>
</html>
_END;

echo $header;

$router = new Router($di);
$response = $router->route(new Request());
echo $response;

echo $footer;
