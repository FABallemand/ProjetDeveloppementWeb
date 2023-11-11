<?php

namespace App\Form;

use App\Entity\Shoe;
use App\Repository\ShelfRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ShoeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand')
            ->add('model')
            ->add('purchased', DateType::class, [
                'widget' => 'single_text'
            ]);

        // Cannot use this feature beacause it generates a weird error when editing a shoe
        // Even the teacher is not able to fix it
        // if($options['display_cupboard']) 
        // {
        //     $builder->add('cupboard', EntityType::class, [
        //         'class' => Cupboard::class
        //         // 'choices' => $group->getUsers(),
        //     ]);
        // }

        if ($options['display_shelves']) {
            $shoe = $options['data'] ?? null;
            $member = $shoe->getCupboard()->getMember();

            $builder
                ->add(
                    'shelves',
                    null,
                    [
                        'query_builder' => function (ShelfRepository $er) use ($member) {
                            return $er->createQueryBuilder('o')
                                ->andWhere('o.member = :member')
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
        }

        $builder
            ->add('imageName', TextType::class, ['disabled' => true])
            ->add('imageFile', VichImageType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shoe::class,
            'display_cupboard' => false, // Disable the feature (see above)
            'display_shelves' => true
        ]);
        $resolver->setAllowedTypes('display_cupboard', 'bool');
        $resolver->setAllowedTypes('display_shelves', 'bool');
    }
}
