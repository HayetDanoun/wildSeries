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
        array &$errors,
        string $uploadDir='/uploads/images/',
        int $maxSize=1,
        array $allowedExtensions=['jpg', 'jpeg', 'png', 'gif', 'webp'],
    ) : ?string
    {
        //$imageFile = $form->get('photo_url') !== null ? $form->get('photo_url')->getData() : null;
        //if ($imageFile) {

        //ici faire les verification coté front si on veut
            if ($imageFile->getSize() > ($maxSize * 1024 * 1024) ) {
                $errors[] = 'La taille de l\'image ne peut pas dépasser ' . $maxSize .' Mo.';
            }

            if (!in_array($imageFile->guessExtension(), $allowedExtensions)) {
                $string = 'Le format de l\'image doit être soit en ';
                foreach ($allowedExtensions as $key => $allowedExtension) {
                    if($key === count($allowedExtensions) -1 ) {
                        $string .= 'ou en ' . $allowedExtension . '.';
                    }
                    else {
                        $string .= $allowedExtension . ', ';
                    }
                }
                $errors[] = $string;

            }
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
            $newFilename = $uploadDir . basename($newFilename);


            try {
                if(empty($errors)) {
                    $imageFile->move(
                        $this->projectDir . '/public' . $uploadDir ,
                        $newFilename
                    );
                    return $newFilename;

                }
            } catch (FileException $e) {
                $errors[] = 'Une erreur est survenue lors de l\'upload de l\'image.';
            }
            return null;
        //}

    }

}
