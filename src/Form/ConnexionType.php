<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 22/02/2018
 * Time: 10:08
 */

namespace App\Form;


use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class ConnexionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pseudo')->add('mdp', PasswordType::class);
    }
}