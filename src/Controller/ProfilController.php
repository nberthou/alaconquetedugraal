<?php
/**
 * Created by PhpStorm.
 * User2: Nicolas
 * Date: 20/02/2018
 * Time: 08:52
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\ConnexionType;
use App\Form\InscriptionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProfilController extends Controller
{

    /**
     * @Route("/profil", name="profil")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $user = $this->getUser();
        return $this->render('Profil/profil.html.twig', array('user' => $user));
    }

    /**
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form  = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $pass = $user->getMdp();
            $mdp = $encoder->encodePassword($user, $pass);
            $user->setMdp($mdp);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('connexion');
        }

        return $this->render('inscription.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/connexion", name="connexion")
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return \Symfony\Component\HttpFoundation\Response
     * @param User $user
     * @ParamConverter("user", class="App\Entity\User", isOptional="true")
     */
    public function connexion(Request $request, AuthenticationUtils $authUtils, User $user) {
        $lastUsername = $authUtils->getLastUsername();
        $error = $authUtils->getLastAuthenticationError();

dump($user);
        if($user->isBanned() == true) {
            $this->addFlash('banni', 'Vous êtes banni de ce site. Veuillez contacter l\'administrateur de ce site pour vous débloquer.');
            return $this->redirectToRoute('connexion');
        }

        return $this->render('connexion.html.twig', array(
            'username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion() {

    }

    /**
     * @Route("/profil/edit_pseudo/{$id}", name="edit_pseudo")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPseudo(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $username = $request->request->get('pseudo');
        $user = $em->getRepository(User::class)->find($id);

        if(!$user) {
            throw $this->createNotFoundException('Pas d\'utilisateur avec l\'id : '.$id);
        }
        $user->setPseudo($username);
        $em->flush();

        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/profil/edit_mdp/{id}", name="edit_mdp")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param UserPasswordEncoderInterface $encoder
     */
    public function editMdp(Request $request, UserPasswordEncoderInterface $encoder) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $mdp = $request->request->get('mdp');
        $user = $em->getRepository(User::class)->find($id);
        $motdepasse= $encoder->encodePassword($user, $mdp);

        if(!$user) {
            throw $this->createNotFoundException('Erreur.');
        }

        $user->setMdp($motdepasse);
        $em->flush();

        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/profil/edit_mail/{id}", name="edit_mail")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editEmail(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $email = $request->request->get('email');
        $user = $em->getRepository(User::class)->find($id);

        if(!$user) {
            throw $this->createNotFoundException('Erreur.');
        }
        $user->setEmail($email);
        $em->flush();

        return $this->redirectToRoute('profil');
    }
}
