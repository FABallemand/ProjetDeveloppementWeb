<?php

namespace App\Form;

use App\Entity\Cupboard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CupboardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name');

        if ($options['display_shoes']) {
            $builder->add('shoes', CollectionType::class, [
                'entry_type' => ShoeType::class,
                'entry_options' => [
                    'display_cupboard' => false,
                    'display_shelves' => false
                ],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ]);
        }

        if ($options['display_member']) {
            $builder->add('member');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cupboard::class,
            'display_member' => true,
            'display_shoes' => false
        ]);
        $resolver->setAllowedTypes('display_member', 'bool');
        $resolver->setAllowedTypes('display_shoes', 'bool');
    }
}
