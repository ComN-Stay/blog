<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UsersType;
use App\Entity\Users;

#[Route('/users')]
class UsersController extends AbstractController
{  

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashPassword);
            $user->setRoles(['ROLE_IDENTIFIED']);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre compte a été créé.'
            );

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('security/login.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
