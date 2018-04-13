<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 10/03/2018
 * Time: 13:30
 */

namespace App\Repository;


use App\Entity\Ami;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\QueryBuilder;

class AmiRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class, Ami::class);
    }

    public function AjoutFindBy($user_id, $statut) {

      return $this->createQueryBuilder('a')
          ->where('a.j1 = :user_id OR a.j2 = :user_id')
          ->andWhere('a.statut = :statut')
          ->setParameter('user_id', $user_id)
          ->setParameter('statut', $statut)
          ->getQuery()
          ->execute();

   }

   public function checkIfFriends($user_id, $ami_id) {
        return $this->createQueryBuilder('a')
            ->where('a.j1 = :user_id OR a.j2 = :user_id')
            ->andWhere('a.j1 = :ami_id OR a.j2 = :ami_id')
            ->setParameter('user_id', $user_id)
            ->setParameter('ami_id', $ami_id)
            ->getQuery()->getOneOrNullResult();
   }
}