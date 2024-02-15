<?php

namespace App\Repository;

use App\Entity\Ruta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ruta>
 *
 * @method Ruta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ruta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ruta[]    findAll()
 * @method Ruta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RutaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ruta::class);
    }

    public function crearRuta($titulo, $fechaInicio, $fechaFin, $aforo, $descripcion, $url_foto, $coordenada)
    {
        //Crea el objeto ruta
        $nuevaRuta = new Ruta();

        //Le añade propiedades
        $nuevaRuta
            ->setTitulo($titulo)
            ->setCoordenadaInicio($coordenada)
            ->setDescripcion($descripcion)
            ->setUrlFoto($url_foto)
            ->setAforo($aforo)
            ->setProgramacion("");

        //Indica que se realice la consulta
        $this->manager->persist($nuevaRuta);

        //Guarda la consulta
        $this->manager->flush();
    }

    public function findByTitulo($titulo): array
    {
        return $this->createQueryBuilder('r')
                    ->andWhere('r.titulo = :val')
                    ->setParameter('val', $titulo)
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('r')
                    ->getQuery()
                    ->getResult()
        ;
    }

//    /**
//     * @return Ruta[] Returns an array of Ruta objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ruta
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
