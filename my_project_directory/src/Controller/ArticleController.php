<?php

namespace App\Controller;

use App\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ArticleType;




final class ArticleController extends AbstractController
{
    #[Route('/Liste', name: 'app_liste')]
    public function List(): Response
    {
        return $this->render('article/Liste.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    #[Route('/create', name: 'app_create')]
    public function Edit(): Response
    {

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        return $this->render('article/Edit.html.twig', [
            'controller_name' => 'ArticleController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function Delete(): Response
    {

        return $this->redirectToRoute('app_liste');
    }
}
