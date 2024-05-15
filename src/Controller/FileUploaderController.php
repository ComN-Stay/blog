<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FileUploaderService;
use App\Repository\PostsMediasRepository;
use App\Entity\Posts;

class FileUploaderController extends AbstractController
{

    #[Route('/upload_file', name: 'app_upload_file', methods: ['POST'])]
    public function upload(
        Request $request,
        FileUploaderService $fileUploader,
        PostsMediasRepository $postsMediasRepository
    ): JsonResponse
    {
        $file = $request->files->get('file');
        if ($file) {
            $fileName = $fileUploader->upload($file, 'uploads');
            return new JsonResponse(['location' => '/assets/img/uploads/' . $fileName]);
        }
        return new JsonResponse(null);
    }
}
