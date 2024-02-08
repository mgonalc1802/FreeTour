<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

   /**
    * @return Item[] Devuelve un array del objeto Item filtrados por localidad
    */
   public function findByLocalidad($localidad): array
   {
        return $this->createQueryBuilder('p')
            ->join('p.localidad', 'l') // Unir la tabla Localidad con el alias 'l'
            ->andWhere('l.nombre LIKE :localidad') // Filtrar por nombre de localidad
            ->setParameter('localidad', $localidad)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
   }

   /**
    * @return Item[] Devuelve un array de objetos del objeto Item filtrados por provincia
    */
    public function findByProvincia($provincia): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.localidad', 'l') // Unir la tabla Localidad con el alias 'l'
            ->join('l.provincia', 'p') // Unir la tabla Provincia con el alias 'p'
            ->andWhere('p.nombre LIKE :provincia') // Filtrar por nombre de provincia
            ->setParameter('provincia', $provincia)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    public function findAll(): array
   {
       return $this->createQueryBuilder('p')
                  ->getQuery()
                  ->getResult()
       ;
   }
}
