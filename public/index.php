<?php
declare(strict_types=1);

use Drawert\Controller\StartQuizController;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

$app->get('/startQuiz', StartQuizController::class . ':startQuiz');
$app->post('/uploadDrawnImage', StartQuizController::class . ':uploadDrawnImage');

$app->add(function(RequestInterface $request, ResponseInterface $response, callable $next) {
    return $response->withHeader('Access-Control-Allow-Origin', '*');
});

$app->run();