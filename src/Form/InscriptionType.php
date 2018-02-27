<?php
/**
 * Created by PhpStorm.
 * InscriptionType: Nicolas
 * Date: 20/02/2018
 * Time: 16:30
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('pseudo', TextType::class)
            ->add('email', EmailType::class)
            ->add('mdp', PasswordType::class)
            ->add('parties_jouees', HiddenType::class,array('empty_data' => 0))
            ->add('is_admin', HiddenType::class, array('empty_data' => false))
            ->add('parties_gagnees', HiddenType::class, array('empty_data' => 0))
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => User::class));
    }
}