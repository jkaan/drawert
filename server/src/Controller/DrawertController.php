<?php
declare(strict_types=1);

namespace Drawert\Controller;

use League\Flysystem\Adapter\Local;
use League\Flysystem\FileExistsException;
use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\ListFiles;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Slim\Http\UploadedFile;

class DrawertController
{
    use MoveUploadedFile;

    private $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem(new Local(__DIR__ . '/../../public/uploads'));
    }

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
        /** @var UploadedFile $uploadedImage */
        $uploadedImage = $uploadedFiles['image'];

        $queryParams = $request->getQueryParams();

        if (!array_key_exists('id', $queryParams) || !Uuid::isValid($queryParams['id'])) {
            return $response->withStatus(400);
        }

        $id = $queryParams['id'];

        $stream = fopen($uploadedImage->file, 'rb+');
        $fileName = $this->generateRandomFilename($uploadedImage);

        try {
            $this->filesystem->writeStream($id . '/' . $fileName, $stream);
        } catch (FileExistsException $e) {
            return $response->withStatus(500, 'File exists');
        }

        $response->getBody()->write(json_encode(['fileName' => $fileName]));

        return $response->withStatus(200);
    }

    public function listImages(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $this->filesystem->addPlugin(new ListFiles());

        $files = $this->filesystem->listFiles('', true);

        $fileNames = [];

        foreach ($files as $file) {
            $fileNames[] = $file['path'];
        }

        $response->getBody()->write(json_encode(['fileNames' => $fileNames]));
        return $response;
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
