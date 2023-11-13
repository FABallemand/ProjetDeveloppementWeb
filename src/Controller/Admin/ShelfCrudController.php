<?php

namespace App\Controller\Admin;

use App\Entity\Shelf;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use App\Controller\Admin\QueryBuilder;

class ShelfCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Shelf::class;
    }

    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id')->hideOnForm(),
    //         TextField::new('name'),
    //         AssociationField::new('member'),
    //         TextField::new('description')->hideOnIndex(),
    //         BooleanField::new('published'),
    //         AssociationField::new('shoes'),
    //         AssociationField::new('shoes')->onlyOnDetail()->setTemplatePath('admin/fields/cupboard_shoes.html.twig'),
    //         DateTimeField::new('created')->hideOnIndex(),
    //         DateTimeField::new('updated')->hideOnIndex()
    //     ];
    // }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            AssociationField::new('member')->hideWhenUpdating(),
            TextField::new('description')->hideOnIndex(),
            // BooleanField::new('published')->onlyOnForms()->hideWhenCreating(),
            // BooleanField::new('published')->hideOnIndex()->hideWhenCreating(),
            BooleanField::new('published')->hideWhenCreating()->renderAsSwitch(false),
            AssociationField::new('shoes'),
            AssociationField::new('shoes')->onlyOnDetail()->setTemplatePath('admin/fields/shelf_shoes.html.twig'),
            // AssociationField::new('shoes')
            //     ->onlyOnForms()
            //     // on ne souhaite pas gérer l'association entre les
            //     // shoe et la shelf dès la crétion de la shelf
            //     ->hideWhenCreating()
            //     ->setTemplatePath('admin/fields/shelf_shoes.html.twig')
            //     // Ajout possible seulement pour des shoe qui
            //     // appartiennent même propriétaire de l'cupboard
            //     // que le member de la shelf
            //     ->setQueryBuilder(
            //         function (QueryBuilder $queryBuilder) {
            //             // récupération de l'instance courante de shelf
            //             $currentShelf = $this->getContext()->getEntity()->getInstance();
            //             $member = $currentShelf->getMember();
            //             $memberId = $member->getId();
            //             // charge les seuls shoe dont le 'owner' du cupboard est le member de la galerie
            //             $queryBuilder->leftJoin('entity.cupboard', 'i')
            //                 ->leftJoin('i.member', 'm')
            //                 ->andWhere('m.id = :member_id')
            //                 ->setParameter('member_id', $memberId);
            //             return $queryBuilder;
            //         }
            //     ),
            DateTimeField::new('created')->hideOnIndex()->hideWhenCreating(),
            DateTimeField::new('updated')->hideOnIndex()->hideWhenCreating()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // For whatever reason show isn't in the menu, bu default
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
