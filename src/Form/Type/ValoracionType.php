<?php

namespace App\Form\Type;

use App\Form\Type\ReservaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextareaType, SubmitType, IntegerType};
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Valoracion;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;


class ValoracionType extends AbstractType
{
    //Indica que necesita un servicio
    private $requestStack;

    //Lo guarda en el constructor
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('guia', IntegerType::class, [
                'constraints' => [
                    //Indica restricciones para el numero de reservas
                    new \Symfony\Component\Validator\Constraints\GreaterThan(['value' => 0, 'message' => 'El valor no puede ser negativo.']),
                    new \Symfony\Component\Validator\Constraints\LessThanOrEqual(['value' => 5, 'message' => 'No puedes poner más puntuación.']), // Ajusta según tus necesidades
                ],
            ])
            ->add('ruta', IntegerType::class, [
                'constraints' => [
                    //Indica restricciones para el numero de reservas
                    new \Symfony\Component\Validator\Constraints\GreaterThan(['value' => 0, 'message' => 'El valor no puede ser negativo.']),
                    new \Symfony\Component\Validator\Constraints\LessThanOrEqual(['value' => 5, 'message' => 'No puedes poner más puntuación.']), // Ajusta según tus necesidades
                ],
            ])
            ->add('comentarios', TextareaType::class, [
                'attr' => [
                    'rows' => 8,  //Establece el número de filas que se mostrarán inicialmente
                    'style' => 'resize: none;',  //Deshabilita la capacidad de cambiar el tamaño
                ]
            ])
            ->add('Valoracion', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Valoracion::class,
            'ruta' => String::class
        ]);
    }
}

?>