<?php

namespace App\Controller\Account;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FileUploaderService;
use App\Repository\UsersRepository;
use App\Repository\PostsRepository;
use App\Repository\PostsMediasRepository;
use App\Form\UsersType;
use App\Entity\Users;

#[Route('/account/users')]
class UsersAccountController extends AbstractController
{
    #[Route('/delete_avatar', name: 'app_users_account_delete_avatar', methods: ['POST'])]
    public function deleteAvatar(
        Request $request, 
        UsersRepository $usersRepository, 
        EntityManagerInterface $entityManager, 
        $kernelUploadDir
    ): JsonResponse
    {
        if ($request->isXMLHttpRequest()) {
            $res['result'] = 'error';
            $user = $usersRepository->find($this->getUser());
            @unlink($kernelUploadDir . '/uploads/' . $user->getAvatar());
            $user->setAvatar(null);
            $entityManager->persist($user);
            $res['result'] = 'success';
            $entityManager->flush();
            return new JsonResponse(json_encode($res));
        }
    
        return new Response('This is not ajax !', 400);
    }

    #[Route('/edit', name: 'app_users_account_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request, 
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository,
        UserPasswordHasherInterface $passwordHasher,
        FileUploaderService $fileUploader,
        Finder $finder,
        $kernelUploadDir
    ): Response
    {
        $user = $usersRepository->find($this->getUser());
        $personas = $finder->files()->in($this->getParameter('kernel.project_dir') . '/public/assets/img/personas');
        $files = [];
        foreach($personas as $file) {
            if(preg_match('#perso\d#', $file->getRelativePathname(), $matches)) {
                $files[$file->getRelativePathname()] = $file->getRelativePathname();
            }
        }
        $form = $this->createForm(UsersType::class, $user, [
            'personas' => $files
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uow = $entityManager->getUnitOfWork();
            $oldValues = $uow->getOriginalEntityData($user);
            if($user->getPassword() != '') {
                $hashPassword = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($hashPassword);
            } else {
                $user->setPassword($oldValues['password']);
            }
            if(null !== $form->get('persona')->getData()) {
                $user->setAvatar($form->get('persona')->getData());
            }
            $file = $form['avatar']->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file, 'personas');
                if (null !== $fileName) {
                    $user->setAvatar($fileName);
                }
            }
            if((null !== $form->get('persona')->getData() || $file) && $oldValues['avatar'] != null) {
                $test = preg_match('#perso\d#', $oldValues['avatar'], $matches);
                if($test == 0) {
                    @unlink($kernelUploadDir . '/personas/' . $oldValues['avatar']);
                }
            }
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Profil modifié avec succés.'
            );

            return $this->redirectToRoute('app_users_account_edit', [], Response::HTTP_SEE_OTHER);
        } 

        return $this->render('account/users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'personas' => $files
        ]);
    }

    #[Route('/{id}', name: 'app_users_account_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Users $user, 
        EntityManagerInterface $entityManager,
        PostsRepository $postsRepository,
        PostsMediasRepository $postsMediasRepository,
        TokenStorageInterface $tokenStorage,
        $kernelUploadDir
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $test = preg_match('#perso\d#', $user->getAvatar(), $matches);
            if($test == 0) {
                @unlink($kernelUploadDir . '/personas/' . $user->getAvatar());
            }
            $posts = $postsRepository->findBy(['fk_user' => $user]);
            foreach($posts as $post) {
                @unlink($kernelUploadDir . '/uploads/' . $post->getPicture());
                $postFiles = $postsMediasRepository->findFilenameByPost($post);
                foreach($postFiles as $file) {
                    @unlink($kernelUploadDir . '/uploads/' . $file);
                }
                $postsMediasRepository->removeByPost($post);
                $entityManager->remove($post);
                $entityManager->flush();
            }
            $entityManager->remove($user);
            $entityManager->flush();
        }

        $request->getSession()->invalidate();
        $tokenStorage->setToken();

        $this->addFlash(
            'success',
            'Votre compte a bien été supprimé'
        );

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
