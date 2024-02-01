<?php

namespace App\Controller\Admin;

use App\Entity\Localidad;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextEditorField, TextField, AssociationField};

class LocalidadCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Localidad::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Nombre'),
            AssociationField::new('provincia')
        ];
    }
    
}
