<?php
declare(strict_types=1);

use Drawert\Controller\DrawertController;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$container = new \Slim\Container($configuration);
$app = new \Slim\App($container);

$app->add(function(RequestInterface $request, ResponseInterface $response, callable $next) {
    $response = $next($request, $response);

    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

$app->get('/startQuiz', DrawertController::class . ':startQuiz');
$app->get('/listDrawnImages', DrawertController::class . ':listImages');
$app->post('/uploadDrawnImage', DrawertController::class . ':uploadDrawnImage');

$app->run();