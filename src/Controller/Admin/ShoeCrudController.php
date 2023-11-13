<?php

namespace App\Controller\Admin;

use App\Entity\Shoe;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class ShoeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Shoe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('brand')->setTemplatePath('admin/fields/shoe_brand.html.twig'),
            TextField::new('model')->setTemplatePath('admin/fields/shoe_model.html.twig'),
            DateField::new('purchased'),
            AssociationField::new('cupboard'),
            AssociationField::new('shelves')->hideOnIndex(),
            AssociationField::new('shelves')->onlyOnDetail()->setTemplatePath('admin/fields/shoe_shelves.html.twig'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // For whatever reason show isn't in the menu, bu default
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    // TODO
    // public function configureCrud(Crud $crud): Crud
    // {
    //     // Customize the rendering of the row to grey-out the completed Todos
    //     return $crud
    //         ->overrideTemplate('crud/index', 'admin/crud/todo_index.html.twig')
    //     ;
    // }
}
