<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, ImageField, TextEditorField, AssociationField};


class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titulo'),
            TextEditorField::new('descripcion'),
            ImageField::new('foto')
                ->setUploadDir("public/fotos")
                ->setBasePath("fotos")
                ->setUploadedFileNamePattern('[contenthash].[extension]')
                ->addHtmlContentsToBody("<img src='/fotos/fotoPregunta1.jpg' class='img-fluid'>")
                ->setFormTypeOption('attr', ['class' => 'wrapper']),
            AssociationField::new("localidad"),
            TextField::new('coordenadas')
        ];
    }
    
}
