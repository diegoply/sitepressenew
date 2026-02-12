<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $nom = $request->request->get('_lastname');

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'lastname' => $nom,
            'error' => $error,
        ]);
    }

   #[Route(path: '/register', name: 'app_register')]
public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
{
    $newUser = new User();
    $form = $this->createForm(UserType::class, $newUser);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $hashedPassword = $passwordHasher->hashPassword($newUser, $form->get('password')->getData());
        $newUser->setPassword($hashedPassword);
        $newUser->setRoles(['ROLE_USER']);

        $em->persist($newUser);
        $em->flush();

        $this->addFlash('success', 'Votre compte a été créé !');
        return $this->redirectToRoute('app_login');
    }

    return $this->render('security/register.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
