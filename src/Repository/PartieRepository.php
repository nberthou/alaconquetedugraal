<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 06/04/2018
 * Time: 10:16
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class PartieRepository extends EntityRepository
{

    public function findByOr($id) {
        return $this->createQueryBuilder('p')
            ->where('p.j1 = :id OR p.j2 = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

}