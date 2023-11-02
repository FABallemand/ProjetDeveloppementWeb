<?php

namespace App\Form;

use App\Entity\Shelf;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShelfType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('published')
            ->add('member')
            ->add('shoes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shelf::class,
            'shelf_is_new' => false
        ]);
        $resolver->setAllowedTypes('shelf_is_new', 'bool');
    }
}