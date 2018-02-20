<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 20/02/2018
 * Time: 08:52
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends Controller
{

    /**
     * @Route("/profil", name="profil")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        return $this->render('Profil/profil.html.twig');
    }

    /**
     * @Route("/login", name="login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request) {

        $form = $this->createFormBuilder()
            ->add('pseudo', TextType::class, array('required' => true, 'label' => 'Pseudonyme'))
            ->add('mdp', PasswordType::class, array('required' => true, 'label' => 'Mot de passe'))
            ->add('envoyer', SubmitType::class, array('label' => 'Se connecter'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            return $this->redirectToRoute('login');
            return $user;
        }

        $nom = $request->request->get('pseudo');

        return $this->render('login.html.twig', array('form' => $form->createView() ));
    }
}