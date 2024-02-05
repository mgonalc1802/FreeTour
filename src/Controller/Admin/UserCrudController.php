<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Action, Actions, Crud, KeyValueStore};
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, EmailField, TextField, ArrayField, ChoiceField, ImageField};
use Symfony\Component\Form\Extension\Core\Type\{PasswordType, RepeatedType};
use Symfony\Component\Form\{FormBuilderInterface, FormEvent, FormEvents};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;


class UserCrudController extends AbstractCrudController
{
    public function __construct
    (
        public UserPasswordHasherInterface $userPasswordHasher
    )
    {

    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return 
        [
            EmailField::new('email'),
            TextField::new('nombre'),
            TextField::new('apellido'),
            TextField::new('apellido2'),
            TextField::new('password')
                ->onlyOnForms()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions(
                    [
                        'type' => PasswordType::class,
                        'first_options' => ['label' => 'Contraseña'],
                        'second_options' => ['label' => 'Repetir Contraseña'],
                        'mapped' => false,
                    ]
                ),
            ArrayField::new("roles"),
            ImageField::new('url_foto')
                ->setUploadDir("public/fotos")
                ->setBasePath("fotos")
                ->setUploadedFileNamePattern('[contenthash].[extension]')
                // ->addHtmlContentsToBody("<img src='/fotos/fotoPregunta1.jpg' class='img-fluid'>")
                ->setFormTypeOption('attr', ['class' => 'wrapper'])
        ];
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() 
    {
        return function($event) 
        {
            $form = $event->getForm();

            if (!$form->isValid()) 
            {
                return;
            }

            $password = $form->get('password')->getData();

            if ($password === null) 
            {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);
        };
    }

}
