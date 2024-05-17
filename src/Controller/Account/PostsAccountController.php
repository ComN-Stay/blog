<?php

namespace App\Controller\Account;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Leapt\ImBundle\Manager;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploaderService;
use App\Repository\PostsRepository;
use App\Form\PostsType;
use App\Entity\Posts;
use App\Entity\PostsMedias;
use App\Repository\PostsMediasRepository;

#[Route('/account/posts')]
class PostsAccountController extends AbstractController
{
    private  $postsMediasRepository;

    public function __construct(PostsMediasRepository $postsMediasRepository)
    {
        $this->postsMediasRepository = $postsMediasRepository;
    }
    #[Route('/', name: 'app_posts_account_index', methods: ['GET'])]
    public function index(
        PostsRepository $postsRepository
    ): Response
    {
        return $this->render('account/posts/index.html.twig', [
            'posts' => $postsRepository->findBy(['fk_user' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_posts_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('posts_account/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/deleteLogo', name: 'app_articles_account_delete_logo', methods: ['POST'])]
    public function deleteLogo(
        Request $request, 
        PostsRepository $postsRepository, 
        EntityManagerInterface $entityManager, 
        $kernelUploadDir
    ): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $res['result'] = 'error';
            $post = $postsRepository->find($request->request->get('id'));
            $logo = $post->getPicture();
            @unlink($kernelUploadDir . '/uploads/' . $logo);
            $post->setPicture(null);
            $entityManager->persist($post);
            $res['result'] = 'success';
            $entityManager->flush();
            return new JsonResponse(json_encode($res));
        }
    
        return new Response('This is not ajax !', 400);
    }

    #[Route('/{id}/edit', name: 'app_posts_account_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        Posts $post, 
        EntityManagerInterface $entityManager,
        FileUploaderService $fileUploader,
        $kernelUploadDir
    ): Response
    {
        if($post->getFkUser() != $this->getUser()) {
            $this->addFlash(
                'error',
                'Vous n\'avez pas accès à cette ressource.'
            );
            return $this->redirectToRoute('app_posts_account_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            preg_match_all('~src="\K[^"]+~', $post->getContent(), $matches);
            $this->manageContentImages($matches, $post, $entityManager, $kernelUploadDir);
            
            $uow = $entityManager->getUnitOfWork();
            $oldValues = $uow->getOriginalEntityData($post);
            $file = $form['picture']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file, 'uploads');
                if (null !== $fileName) {
                    $post->setPicture($fileName);
                }
            } else {
                $post->setPicture($oldValues['picture']);
            }
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Mise à jour effectuée !'
            );

            return $this->redirectToRoute('app_posts_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/posts/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}', name: 'app_posts_account_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Posts $post, 
        EntityManagerInterface $entityManager,
        $kernelUploadDir
    ): Response
    {
        if($post->getFkUser() != $this->getUser()) {
            $this->addFlash(
                'error',
                'Vous n\'avez pas accès à cette ressource.'
            );
            return $this->redirectToRoute('app_posts_account_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->get('_token'))) {
            @unlink($kernelUploadDir . '/uploads/' . $post->getPicture());
            $postFiles = $this->postsMediasRepository->findFilenameByPost($post);
            foreach($postFiles as $file) {
                @unlink($kernelUploadDir . '/uploads/' . $file);
            }
            $this->postsMediasRepository->removeByPost($post);
            $entityManager->remove($post);
            $entityManager->flush();
        }
            
        return $this->redirectToRoute('app_posts_account_index', [], Response::HTTP_SEE_OTHER);
    }

    private function manageContentImages($matches, $post, $entityManager, $kernelUploadDir)
    {
        $postFiles = $this->postsMediasRepository->findFilenameByPost($post);
        $filenames = [];
        foreach($matches[0] as $match) {
            array_push($filenames, basename($match));
            if(!$this->recursive_array_search(basename($match), $postFiles)) {
                $postsMedias = new PostsMedias;
                $postsMedias->setFkPost($post);
                $postsMedias->setFilename(basename($match));
                $entityManager->persist($postsMedias);
            }
        }
        $entityManager->flush();
    
        foreach($postFiles as $file) {
            if(!$this->recursive_array_search($file['filename'], $filenames)) {
                @unlink($kernelUploadDir . '/uploads/' . $file['filename']);
                $this->postsMediasRepository->deleteByFilename($file['filename']);
            }
        }
    }

    private function recursive_array_search($needle, $haystack, $currentKey = '') 
    {
        foreach($haystack as $key=>$value) {
            if (is_array($value)) {
                $nextKey = $this->recursive_array_search($needle,$value, $currentKey . '[' . $key . ']');
                if ($nextKey) {
                    return $nextKey;
                }
            }
            else if($value==$needle) {
                return true;
            }
        }
        return false;
    }
    
}
    