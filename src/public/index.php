<?php
declare(strict_types=1);

use Drawert\Controller\StartQuizController;

require __DIR__ . '/../../vendor/autoload.php';

$app = new \Slim\App();

$app->get('/startQuiz', StartQuizController::class . ':startQuiz');

$app->run();