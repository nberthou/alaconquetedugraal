<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 18/03/2018
 * Time: 18:03
 */

namespace App\Repository;


use App\Entity\Partie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping;
use Doctrine\ORM\EntityRepository;

class PartieRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class, Partie::class);
    }

    public function EtatFindBy($user_id, $etat) {
        return $this->createQueryBuilder('p')
            ->where('p.j1 = :user_id OR p.j2 = :user_id')
            ->andWhere('p.etat = :etat')
            ->setParameter('user_id', $user_id)
            ->setParameter('etat', $etat)
            ->getQuery()
            ->execute();
    }
}