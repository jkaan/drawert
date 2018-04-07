<?php
declare(strict_types=1);

namespace Drawert\Controller;

use Slim\Http\UploadedFile;

trait MoveUploadedFile
{
    /**
     * Moves the uploaded file to the upload directory and assigns it a unique name
     * to avoid overwriting an existing uploaded file.
     *
     * @param string $directory directory to which the file is moved
     * @param UploadedFile $uploaded file uploaded file to move
     * @return string filename of moved file
     */
    public function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $filename = $this->generateRandomFilename($uploadedFile);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @return string
     */
    public function generateRandomFilename(UploadedFile $uploadedFile): string
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        return sprintf('%s.%0.8s', $basename, $extension);
    }
}
