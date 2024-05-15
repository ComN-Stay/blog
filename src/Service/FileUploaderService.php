<?php 

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Leapt\ImBundle\Manager;

class FileUploaderService
{
    private $kernelUploadDir;
    private $slugger;
    private $imManager;

    public function __construct(
        $kernelUploadDir, 
        SluggerInterface $slugger,
        Manager $imManager
    )
    {
        $this->kernelUploadDir = $kernelUploadDir;
        $this->slugger = $slugger;
        $this->imManager = $imManager;
    }

    public function upload(UploadedFile $file, $folder)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move($this->getTargetDirectory($folder), $fileName);
            $this->imManager->mogrify('medium', $this->kernelUploadDir . '/' . $folder . '/' . $fileName);
        } catch (FileException $e) {
            return null;
        }
        return $fileName;
    }
    
    public function getTargetDirectory($folder)
    {
        return $this->kernelUploadDir . '/' . $folder;
    }
}