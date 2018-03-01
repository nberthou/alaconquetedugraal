<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 21/02/2018
 * Time: 11:42
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where ('u.pseudo = :pseudo OR u.email = :email')
            ->setParameter('pseudo', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }


}