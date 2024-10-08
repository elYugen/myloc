<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Items;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de l\'objet',
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter une image',
                'mapped' => false,
                
            ])
            ->add('description')
            ->add('category', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'label' => 'Catégorie'
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-warning'],
                'label' => 'Enregistrer'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Items::class,
        ]);
    }
}
