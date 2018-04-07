<?php
declare(strict_types=1);

namespace Drawert\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;

class StartQuizController
{
    use MoveUploadedFile;

    public function startQuiz(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $listOfLogos = $this->getListOfLogos();
        $listOfNonLogos = $this->getListOfNonLogos();

        $listToReturn = array_values(array_merge($listOfLogos, $listOfNonLogos));
        shuffle($listToReturn);

        $response->getBody()->write(json_encode(
            [
                'logos' => $listToReturn,
                'id' => Uuid::uuid4()->toString()
            ]
        ));

        return $response;
    }

    public function uploadDrawnImage(ServerRequestInterface $request, ResponseInterface $response)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedImage = $uploadedFiles['image'];

        $queryParams = $request->getQueryParams();

        if (array_key_exists('id', $queryParams) && Uuid::isValid($queryParams['id'])) {
            $id = $queryParams['id'];

            // Create directory if needed
            if (!file_exists(__DIR__ . '/../../uploads/' . $id)
                && !mkdir(__DIR__ . '/../../uploads/' . $id, 0777, true)
                && !is_dir(__DIR__ . '/../../uploads/' . $id)) {
                throw new \RuntimeException('Error error, uh uh');
            }
        }

        // Create the file using the ID that has been retrieved from the request
        $fileName = $this->moveUploadedFile(__DIR__ . '/../../uploads/' . $id, $uploadedImage);
        $response->getBody()->write(json_encode(['fileName' => $fileName]));

        return $response->withStatus(200);
    }

    private function getListOfLogos(): array
    {
        // Get random list of 3 logos
        $listOfLogos = [];

        $availableLogos = require __DIR__ . '/../../public/logos.php';

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

        $availableNonLogos = require __DIR__ . '/../../public/nonLogos.php';

        // Randomly select 5 names out of logos.php
        while (count($listOfNonLogos) < 2) {
            $listOfNonLogos[] = $availableNonLogos[random_int(0, count($availableNonLogos) - 1)];
            $listOfNonLogos = array_unique($listOfNonLogos);
        }
        return $listOfNonLogos;
    }
}
