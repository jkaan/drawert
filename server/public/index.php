<?php
declare(strict_types=1);

use Drawert\Controller\StartQuizController;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

error_reporting(E_ALL);
ini_set("display_errors", '1');
ini_set("log_errors", '1');
ini_set("catch_workers_output", "yes");

require __DIR__ . '/../vendor/autoload.php';

$configuration = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$container = new \Slim\Container($configuration);
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        error_log($c);

        return $c['response']->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write('Something went wrong!');
    };
};
$app = new \Slim\App($container);

$app->add(function(RequestInterface $request, ResponseInterface $response, callable $next) {
    $response = $next($request, $response);

    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

$app->get('/startQuiz', StartQuizController::class . ':startQuiz');
$app->post('/uploadDrawnImage', StartQuizController::class . ':uploadDrawnImage');

$app->run();