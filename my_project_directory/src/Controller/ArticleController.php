<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\ArticleNote;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
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
public function Show(Article $article, Request $request, EntityManagerInterface $em): Response
{
    $newComment = new Comment();
    $newComment->setArticle($article);

    $form = $this->createForm(CommentType::class, $newComment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid() && $this->getUser()) {
        $newComment->setUser($this->getUser());
        $newComment->setPublishedAt(new \DateTimeImmutable());

        $em->persist($newComment);

         // ⚡ Création de la note
        $noteValue = $form->get('note')->getData();
        if ($noteValue) {
            $articleNote = new ArticleNote();
            $articleNote->setArticle($article);
            $articleNote->setUser($this->getUser());
            $articleNote->setNote($noteValue);

            $em->persist($articleNote);
        }

        $em->flush();

        // ⚡ Redirige pour recharger correctement les commentaires
        return $this->redirectToRoute('app_show', ['id' => $article->getId()]);
    }

    // Récupération correcte des commentaires depuis la base
    $commentsInDb = $article->getComment();
    // Récupération Notes
    // 🔹 Récupérer toutes les notes de l'article
    $articleNotes = $em->getRepository(ArticleNote::class)
        ->findBy(['article' => $article]);
   
    //dd($articleNotes);

    //Calcul Moyenne notes
    $averageNote = 0;
    if (count($articleNotes) > 0) {
        $total = array_sum(array_map(fn($n) => $n->getNote(), $articleNotes));
        $averageNote = round($total / count($articleNotes), 1);
    }
    

    return $this->render('article/Show.html.twig', [
        'article' => $article,
        'form' => $form,
        'comments' => $commentsInDb,
        'averageNote' => $averageNote,
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
        if ($form->isSubmitted() && $form->isValid() && $this->getUser()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $article = $form->getData();
            //$article->setStatus("DRAFT");
            $article->setPublishedAt(new \DateTimeImmutable());
            

            $article->setUser($this->getUser());
            //dd($article);


           //dd($article);

            $em->persist($article);
            $em->flush();

            
            $this->addFlash('success', $isCreate ? 'L/article a etait créé' : 'L/article a etait modifier');
             

            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_liste');
        }

        return $this->render('article/Edit.html.twig', [
            'form' => $form,
            'is_create' => $isCreate,
        ]);
    }

    #[Route('/deleteComment/{id}', name: 'app_deleteComment')]
public function DeleteComment(
    int $id,
    CommentRepository $commentRepository,
    EntityManagerInterface $em
): Response {

    $comment = $commentRepository->find($id);

    if (!$comment) {
        $this->addFlash('error', "Commentaire introuvable !");
        return $this->redirectToRoute('app_liste'); // ou app_show selon ton cas
    }

    $article = $comment->getArticle();

    $em->remove($comment);
    $em->flush();

    return $this->redirectToRoute('app_show', [
        'id' => $article->getId()
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
