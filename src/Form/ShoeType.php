<?php

namespace App\Form;

use App\Entity\Shoe;
use App\Entity\Cupboard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        // if($options['display_cupboard'] ) 
        // {
        //     $builder->add('cupboard', EntityType::class, [
        //         'class' => Cupboard::class
        //         // 'choices' => $group->getUsers(),
        //     ]);
        // }

        $builder
            ->add('shelves')
            ->add('imageName', TextType::class, ['disabled' => true])
            ->add('imageFile', VichImageType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shoe::class,
            'display_cupboard' => false // Disable the feature (see above)
        ]);
        $resolver->setAllowedTypes('display_cupboard', 'bool');
    }
}
