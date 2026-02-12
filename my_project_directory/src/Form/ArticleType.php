<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Article;
use App\Entity\ArticleCategory;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre Article',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenue',
            ])
            ->add('article_category', EntityType::class, [
            'class' => ArticleCategory::class,  // l'entité liée
            'choice_label' => 'name',          // ce qui sera affiché dans le select
            'label' => 'Catégorie',
            'placeholder' => 'Choisir une catégorie', // optionnel
            'required' => true,                // ou false si facultatif
            ])
            
            ->add('status', ChoiceType::class, [
            'label' => 'Statut de l\'article',
            'choices' => [
            'Brouillon' => 'DRAFT',
            'Publié' => 'PUBLISHED',
        '   Archivé' => 'ARCHIVED',
            ],
            'expanded' => false, // true = boutons radio, false = select
    
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Soumettre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
