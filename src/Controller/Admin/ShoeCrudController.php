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
            TextField::new('brand')->setTemplatePath('admin/fields/shoe_index_brand.html.twig'),
            TextField::new('model')->setTemplatePath('admin/fields/shoe_index_model.html.twig'),
            AssociationField::new('cupboard')
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
