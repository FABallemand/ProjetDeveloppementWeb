<?php

namespace App\Form;

use App\Entity\Shelf;
use App\Repository\ShoeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShelfType extends AbstractType
{
    // public function buildForm(FormBuilderInterface $builder, array $options): void
    // {
    //     $builder
    //         ->add('name')
    //         ->add('description')
    //         ->add('published')
    //         ->add('member')
    //         ->add('shoes')
    //     ;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dump($options);
        $shelf = $options['data'] ?? null;
        $member = $shelf->getMember();

        $builder
            ->add('name')
            ->add('description')
            ->add('published')
            // ->add('created', null, ['disabled' => true,])
            // ->add('updated', null, ['disabled' => true,])
            ->add(
                'shoes',
                null,
                [
                    'query_builder' => function (ShoeRepository $er) use ($member) {
                        return $er->createQueryBuilder('o')
                            ->leftJoin('o.cupboard', 'i')
                            ->andWhere('i.member = :member')
                            ->setParameter('member', $member);
                    },
                    // avec 'by_reference' => false, sauvegarde les modifications
                    'by_reference' => false,
                    // classe pas obligatoire
                    //'class' => [Object]::class,
                    // permet sélection multiple
                    'multiple' => true,
                    // affiche sous forme de checkboxes
                    'expanded' => true
                ]
            );

        if ($options['show_member']) {
            $builder->add('member');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shelf::class,
            'show_member' => false
        ]);
        $resolver->setAllowedTypes('show_member', 'bool');
    }
}
