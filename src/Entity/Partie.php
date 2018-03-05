<?php
/**
 * Created by PhpStorm.
 * User2: Nicolas
 * Date: 20/02/2018
 * Time: 10:23
 */

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * Class Partie
 * @package App\Entity
 * @ORM\Entity
 */
class Partie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $partie_nom;

    /**
     * @ORM\Column(type="text")
     */
    private $main_j1; // text --> tableau JSON

    /**
     * @ORM\Column(type="text")
     */
    private $main_j2; // text --> tableau JSON

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $j1; // liaison

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $j2; // liaison

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $score_j1; // int

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $score_j2; // int

    /**
     * @ORM\Column(type="integer")
     */
    private $tour; // "J1 ou J2" OU liaison

    /**
     * @ORM\Column(type="integer")
     */
    private $manche; // int

    /**
     * @ORM\Column(type="text")
     */
    private $pioche; // text --> tableau avec cartes restantes

    /**
     * @ORM\Column(type="text")
     */
    private $objectifs; // text --> tableau [id_objectif : id_joueur]

    /**
     * @ORM\Column(type="text")
     */
    private $carte_jetee; // text --> id de la carte

    /**
     * @ORM\Column(type="text")
     */
    private $action_j1; // tableau JSON

    /**
     * @ORM\Column(type="text")
     */
    private $action_j2; // tableau JSON

    /**
     * @ORM\Column(type="text")
     */
    private $terrain_j1; // tableau JSON

    /**
     * @ORM\Column(type="text")
     */
    private $terrain_j2;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPartieNom()
    {
        return $this->partie_nom;
    }

    /**
     * @param mixed $partie_nom
     */
    public function setPartieNom($partie_nom)
    {
        $this->partie_nom = $partie_nom;
    }

    /**
     * @return mixed
     */
    public function getMainJ1()
    {
        return $this->main_j1;
    }

    /**
     * @param mixed $main_j1
     */
    public function setMainJ1($main_j1)
    {
        $this->main_j1 = $main_j1;
    }

    /**
     * @return mixed
     */
    public function getMainJ2()
    {
        return $this->main_j2;
    }

    /**
     * @param mixed $main_j2
     */
    public function setMainJ2($main_j2)
    {
        $this->main_j2 = $main_j2;
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
     * @return int
     */
    public function getScoreJ1()
    {
        return $this->score_j1;
    }

    /**
     * @param int $score_j1
     */
    public function setScoreJ1($score_j1)
    {
        $this->score_j1 = $score_j1;
    }

    /**
     * @return int
     */
    public function getScoreJ2()
    {
        return $this->score_j2;
    }

    /**
     * @param int $score_j2
     */
    public function setScoreJ2($score_j2)
    {
        $this->score_j2 = $score_j2;
    }

    /**
     * @return mixed
     */
    public function getTour()
    {
        return $this->tour;
    }

    /**
     * @param mixed $tour
     */
    public function setTour($tour)
    {
        $this->tour = $tour;
    }

    /**
     * @return mixed
     */
    public function getManche()
    {
        return $this->manche;
    }

    /**
     * @param mixed $manche
     */
    public function setManche($manche)
    {
        $this->manche = $manche;
    }

    /**
     * @return mixed
     */
    public function getPioche()
    {
        return $this->pioche;
    }

    /**
     * @param mixed $pioche
     */
    public function setPioche($pioche)
    {
        $this->pioche = $pioche;
    }

    /**
     * @return mixed
     */
    public function getObjectifs()
    {
        return $this->objectifs;
    }

    /**
     * @param mixed $objectifs
     */
    public function setObjectifs($objectifs)
    {
        $this->objectifs = $objectifs;
    }

    /**
     * @return mixed
     */
    public function getCarteJetee()
    {
        return $this->carte_jetee;
    }

    /**
     * @param mixed $carte_jetee
     */
    public function setCarteJetee($carte_jetee)
    {
        $this->carte_jetee = $carte_jetee;
    }

    /**
     * @return mixed
     */
    public function getActionJ1()
    {
        return $this->action_j1;
    }

    /**
     * @param mixed $action_j1
     */
    public function setActionJ1($action_j1)
    {
        $this->action_j1 = $action_j1;
    }

    /**
     * @return mixed
     */
    public function getActionJ2()
    {
        return $this->action_j2;
    }

    /**
     * @param mixed $action_j2
     */
    public function setActionJ2($action_j2)
    {
        $this->action_j2 = $action_j2;
    }

    /**
     * @return mixed
     */
    public function getTerrainJ1()
    {
        return $this->terrain_j1;
    }

    /**
     * @param mixed $terrain_j1
     */
    public function setTerrainJ1($terrain_j1)
    {
        $this->terrain_j1 = $terrain_j1;
    }

    /**
     * @return mixed
     */
    public function getTerrainJ2()
    {
        return $this->terrain_j2;
    }

    /**
     * @param mixed $terrain_j2
     */
    public function setTerrainJ2($terrain_j2)
    {
        $this->terrain_j2 = $terrain_j2;
    }
    
}