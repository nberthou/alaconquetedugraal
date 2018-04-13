<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 01/03/2018
 * Time: 14:55
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Chat
 * @package App\Entity
 * @ORM\Entity
 */
class Chat
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;



}