<?php

namespace App\Controller\Admin;

use App\Entity\Cupboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CupboardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cupboard::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name')->setTemplatePath('admin/fields/cupboard_index_name.html.twig'),
            AssociationField::new('shoes'),
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
