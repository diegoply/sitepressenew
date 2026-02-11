<?php

namespace App\Controller;

use App\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class ArticleController extends AbstractController
{
    #[Route('/Liste', name: 'app_liste')]
    public function List(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/Liste.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/Show/{id}', name: 'app_show')]
    public function Show(ArticleRepository $articleRepository, Article $article): Response
    {
        return $this->render('article/Show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    #[Route('/create', name: 'app_create')]
    public function Edit(Request $request, EntityManagerInterface $em, ?Article $article = NULL): Response
    {

        $isCreate = false;

        if(!$article){

            $isCreate = true;
            $article = new Article();
        }

        

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $article = $form->getData();
            $article->setStatus("DRAFT");
            $article->setPublishedAt(new \DateTimeImmutable());
            

            $article->setUser($this->getUser());


           //dd($article);

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', $isCreate ? 'L/article a etait créé' : 'L/article a etait modifier');
            // dd($article);

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_liste');
        }

        return $this->render('article/Edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete')]
    public function Delete(EntityManagerInterface $em, Article $article): Response
    {

        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('app_liste');
    }
}
