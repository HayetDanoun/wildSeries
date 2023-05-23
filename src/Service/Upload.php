<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Form;

class Upload
{
    private $projectDir;
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    //allowedExtensions par defaut est une image
    //maxSize est en Mo
    public function uploadFile(
        UploadedFile $imageFile,
        string $uploadDir='/uploads/images/',
    ) : string
    {
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            $newFilename = $uploadDir . basename($newFilename);


            try {
                if(empty($errors)) {
                    $imageFile->move(
                        $this->projectDir . '/public' . $uploadDir ,
                        $newFilename
                    );

                }
            } catch (FileException $e) {
                //$errors[] = 'Une erreur est survenue lors de l\'upload de l\'image.';
            }
        return $newFilename;
    }

}
