<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Ulid;

class FileUploadService {
    public function __construct(
        private string $projectDir,
    ) {
    }

    public function handleUploadedFile(UploadedFile $file) {
        $destination = $this->projectDir.'/public/uploads/';

        $newFilename = Ulid::generate().'.'.$file->guessExtension();

        $file->move(
            $destination,
            $newFilename
        );

        return $newFilename;
    }
}