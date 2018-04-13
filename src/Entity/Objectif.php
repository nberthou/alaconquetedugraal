<?php
/**
 * Created by PhpStorm.
 * User2: Nicolas
 * Date: 20/02/2018
 * Time: 10:29
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * Class Objectif
 * @package App\Entity
 * @ORM\Entity
 *
 */
class Objectif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="text")
     */
    protected $img;
    /**
     * @ORM\Column(type="integer")
     */
    protected $nbpoints;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }

    /**
     * @return mixed
     */
    public function getNbpoints()
    {
        return $this->nbpoints;
    }

    /**
     * @param mixed $nbpoints
     */
    public function setNbpoints($nbpoints)
    {
        $this->nbpoints = $nbpoints;
    }


}