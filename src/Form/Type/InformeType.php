<?php

namespace App\Form\Type;

use App\Form\Type\InformeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextareaType, SubmitType, IntegerType, NumberType, FileType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\{Reserva, Ruta, Tour, Valoracion, User, Informe};
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\TourRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class InformeType extends AbstractType
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
            ->add('participantes', IntegerType::class, [
                'constraints' => [
                    //Indica restricciones para el numero de reservas
                    new \Symfony\Component\Validator\Constraints\GreaterThan(['value' => 0, 'message' => 'El valor no puede ser negativo.']),
                    new \Symfony\Component\Validator\Constraints\LessThanOrEqual(['value' => 5, 'message' => 'No puedes reservar para más de 5 personas.']), 
                ],
            ])
            ->add('dinero_recaudado', NumberType::class)
            ->add('foto_grupal', FileType::class, [
                'attr' => [
                    'accept' => 'image/*',
                ],
            ])
            ->add('descripcion', TextareaType::class, [
                'attr' => [
                    'rows' => 8,  //Establece el número de filas que se mostrarán inicialmente
                    'style' => 'resize: none;',  //Deshabilita la capacidad de cambiar el tamaño
                ]
            ])
            ->add('Informe', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Informe::class
        ]);
    }
}

?>