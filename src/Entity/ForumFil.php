<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 20/03/2018
 * Time: 15:27
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Forum_fil
 * @package App\Entity
 * @ORM\Entity
 */
class ForumFil
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $sujet;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;
}