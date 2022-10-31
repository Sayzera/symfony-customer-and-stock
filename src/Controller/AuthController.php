<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AuthType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->getManager = $doctrine->getManager();
    }

    #[Route('/register', name: 'register')]
    public function register(UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, Request $request): Response
    {
        $user = new User();

        $form =  $this->createForm(AuthType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashhedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashhedPassword);


            $this->getManager->persist($user);
            $this->getManager->flush();

            $this->addFlash('success', 'Kullanıcı başarıyla oluşturuldu.');
        }

        return $this->render('auth/register.html.twig', [
            'controller_name' => 'AuthController',
            'form' => $form->createView()
        ]);
    }
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('auth/login.html.twig', [
            'controller_name' => 'AuthController',
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
