<?php
/**
 * Created by PhpStorm.
 * InscriptionType: Nicolas
 * Date: 20/02/2018
 * Time: 15:56
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class InscriptionType
 * @package App\Entity
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Adresse mail déjà prise.")
 * @UniqueEntity(fields="pseudo", message="Pseudo déjà pris.")
 */
class User implements UserInterface, \JsonSerializable
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $mdp;

    /**
     * @ORM\Column(type="integer")
     */
    private $parties_gagnees;

    /**
     * @ORM\Column(type="integer")
     */
    private $parties_jouees;

    /**
     * @ORM\Column(type="json_array")
     * @var array
     */
    private $roles = array("ROLE_USER");



    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @var boolean
     */
    private $banned;


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
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     */
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }

    /**
     * @return mixed
     */
    public function getPartiesJouees()
    {
        return $this->parties_jouees;
    }

    /**
     * @param mixed $parties_jouees
     */
    public function setPartiesJouees($parties_jouees)
    {
        $this->parties_jouees = $parties_jouees;
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->getMdp();
    }

    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param $roles
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    public function getUsername()
    {
        return $this->pseudo;
    }

    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getPartiesGagnees()
    {
        return $this->parties_gagnees;
    }

    /**
     * @param mixed $parties_gagnees
     */
    public function setPartiesGagnees($parties_gagnees)
    {
        $this->parties_gagnees = $parties_gagnees;
    }


    /**
     * @return bool
     */
    public function isBanned()
    {
        return $this->banned;
    }

    /**
     * @param bool $banned
     */
    public function setBanned($banned)
    {
        $this->banned = $banned;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }



}
