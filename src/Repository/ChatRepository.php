<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 10/04/2018
 * Time: 20:28
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class ChatRepository extends EntityRepository
{
    public function selectMessages() {
        return $this->createQueryBuilder('c')
            ->orderBy('c.date', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}