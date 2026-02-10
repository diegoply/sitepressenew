<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère le dernier username entré
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('index/index.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }
}
