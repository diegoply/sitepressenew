<?php

namespace App\Controller;

use App\Entity\ArticleCategory;
use App\Form\ArticleCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

final class CategoryController extends AbstractController
{

#[Route('/category/new', name: 'app_category_new')]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $category = new ArticleCategory();
    $form = $this->createForm(ArticleCategoryType::class, $category);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        //dd($category);
    
        $em->persist($category);
        $em->flush();

        //dd($category);

       //return $this->redirectToRoute('app_category_new');
    }

    return $this->render('category/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

}