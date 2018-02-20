<?php
/**
 * Created by PhpStorm.
 * User2: Nicolas
 * Date: 20/02/2018
 * Time: 08:52
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inscription(Request $request) {
        $user = new User();

        $form  = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('connexion');
        }

        return $this->render('inscription.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/connexion", name="connexion")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function connexion() {
        return $this->render('connexion.html.twig');
    }
}