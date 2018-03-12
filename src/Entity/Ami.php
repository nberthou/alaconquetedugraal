<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 09/03/2018
 * Time: 23:21
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ami
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AmiRepository")
 */
class Ami
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $j1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $j2;

    /**
     * @ORM\Column(type="smallint")
     */
    private $statut;

    /**
     * @ORM\Column(type="integer")
     */
    private $derniere_action;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getJ1()
    {
        return $this->j1;
    }

    /**
     * @param mixed $j1
     */
    public function setJ1($j1)
    {
        $this->j1 = $j1;
    }

    /**
     * @return mixed
     */
    public function getJ2()
    {
        return $this->j2;
    }

    /**
     * @param mixed $j2
     */
    public function setJ2($j2)
    {
        $this->j2 = $j2;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * @return mixed
     */
    public function getDerniereAction()
    {
        return $this->derniere_action;
    }

    /**
     * @param mixed $derniere_action
     */
    public function setDerniereAction($derniere_action)
    {
        $this->derniere_action = $derniere_action;
    }



}