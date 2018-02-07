<?php
declare(strict_types=1);

namespace Drawert\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class StartQuizController
{
    public function startQuiz(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $listOfLogos = $this->getListOfLogos();
        $listOfNonLogos = $this->getListOfNonLogos();

        $listToReturn = array_values(array_merge($listOfLogos, $listOfNonLogos));
        shuffle($listToReturn);

        $response->getBody()->write(json_encode($listToReturn));

        return $response;
    }

    private function getListOfLogos(): array
    {
        // Get random list of 3 logos
        $listOfLogos = [];

        $availableLogos = require __DIR__ . '/../public/logos.php';

        // Randomly select 5 names out of logos.php
        while (count($listOfLogos) < 3) {
            $listOfLogos[] = $availableLogos[random_int(0, count($availableLogos) - 1)];
            $listOfLogos = array_unique($listOfLogos);
        }
        return $listOfLogos;
    }

    private function getListOfNonLogos(): array
    {
        // Get random list of 3 nonLogos
        $listOfNonLogos = [];

        $availableNonLogos = require __DIR__ . '/../public/nonLogos.php';

        // Randomly select 5 names out of logos.php
        while (count($listOfNonLogos) < 2) {
            $listOfNonLogos[] = $availableNonLogos[random_int(0, count($availableNonLogos) - 1)];
            $listOfNonLogos = array_unique($listOfNonLogos);
        }
        return $listOfNonLogos;
    }
}
