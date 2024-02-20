<?php

namespace App\Form\Type;

use App\Form\Type\ReservaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\{Reserva, Ruta, Tour, Valoracion, User};
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\TourRepository;
use Symfony\Component\HttpFoundation\RequestStack;


class ReservaType extends AbstractType
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
            ->add('numero_reservas', IntegerType::class, [
                'constraints' => [
                    //Indica restricciones para el numero de reservas
                    new \Symfony\Component\Validator\Constraints\GreaterThan(['value' => 0, 'message' => 'El valor no puede ser negativo.']),
                    new \Symfony\Component\Validator\Constraints\LessThanOrEqual(['value' => 5, 'message' => 'No puedes reservar para más de 5 personas.']), // Ajusta según tus necesidades
                ],
            ])
            ->add('tour', EntityType::class, [
                'class' => Tour::class,
                'query_builder' => function (TourRepository $er) 
                {
                    //Llama al método y le indica que obtenga la url
                    $request = $this->requestStack->getCurrentRequest();

                    //De esa url obtiene el atributo id
                    $rutaId = $request->attributes->get('id');

                    //Realiza la query
                    return $er->createQueryBuilder('t')
                        ->where('t.ruta_id = :id')
                        ->setParameter('id', $rutaId)
                        ->andWhere('t.fecha > :val')
                        ->setParameter('val', new \DateTime());
                },
             ])
            ->add('Reserva', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
            'ruta' => String::class
        ]);
    }
}

?>