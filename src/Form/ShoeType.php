<?php

namespace App\Form;

use App\Entity\Shoe;
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

        if($options['display_cupboard'] ) 
        {
            $builder->add('cupboard');
        }
            
        $builder
            ->add('shelves')
            ->add('imageName', TextType::class, ['disabled' => true])
            ->add('imageFile', VichImageType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shoe::class,
            'display_cupboard' => true
        ]);
        $resolver->setAllowedTypes('display_cupboard', 'bool');
    }
}
