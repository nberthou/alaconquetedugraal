<?php
/**
 * Created by PhpStorm.
 * UserType: Nicolas
 * Date: 20/02/2018
 * Time: 15:56
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserType
 * @package App\Entity
 * @ORM\Entity
 * @UniqueEntity(fields="email", message="Adresse mail déjà prise.")
 * @UniqueEntity(fields="pseudo", message="Pseudo déjà pris.")
 */
class User implements UserInterface
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
    private $parties_jouees;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_admin;

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
    public function getisAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param mixed $is_admin
     */
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }

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
        return null;
    }

    public function getUsername()
    {
        return $this->getPseudo();
    }

    public function eraseCredentials()
    {
        return null;
    }
}