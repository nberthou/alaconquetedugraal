<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 20/03/2018
 * Time: 15:30
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ForumPost
 * @package App\Entity
 * @ORM\Entity
 */
class ForumPost
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
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumFil")
     */
    private $fil;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;
}