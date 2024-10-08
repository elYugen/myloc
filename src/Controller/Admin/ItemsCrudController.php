<?php

namespace App\Controller\Admin;

use App\Entity\Items;
use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Security\Core\Security;

class ItemsCrudController extends AbstractCrudController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Items::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextEditorField::new('description'),
            TextField::new('image'),
            AssociationField::new('category')
                ->setFormTypeOptions([
                    'class' => Categories::class,
                    'choice_label' => 'name',
                ]),
            AssociationField::new('owner')
                ->setFormTypeOption('disabled', true)
                ->hideOnForm(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $item = new Items();
        $item->setOwner($this->security->getUser());
        return $item;
    }

}