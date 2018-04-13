<?php
/**
 * Created by PhpStorm.
 * User2: Nicolas
 * Date: 20/02/2018
 * Time: 08:52
 */

namespace App\Controller;


use App\Entity\Ami;
use App\Entity\Partie;
use App\Entity\User;
use App\Form\ConnexionType;
use App\Form\InscriptionType;
use App\Repository\AmiRepository;
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
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $userId = $user->getId();
        $joueurs = $this->getDoctrine()->getRepository(User::class)->findAll();
        $amis = $this->getDoctrine()->getRepository(Ami::class)->AjoutFindBy($userId, 1);
        $amisAttente = $this->getDoctrine()->getRepository(Ami::class)->AjoutFindBy($userId, 0);
        $parties = $this->getDoctrine()->getRepository(Partie::class)->findByOr($userId);
        return $this->render('Profil/profil.html.twig', array('user' => $user, 'joueurs' => $joueurs, 'amis' => $amis, 'amisAttente' => $amisAttente, 'parties' => $parties));
    }

    /**
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
     */
    public function connexion(Request $request, AuthenticationUtils $authUtils)
    {

        $lastUsername = $authUtils->getLastUsername();
        $error = $authUtils->getLastAuthenticationError();



        return $this->render('connexion.html.twig', array(
            'username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnexion()
    {

    }

    /**
     * @Route("/profil/edit_pseudo/{$id}", name="edit_pseudo")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editPseudo(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $username = $request->request->get('pseudo');
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Pas d\'utilisateur avec l\'id : ' . $id);
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
    public function editMdp(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $mdp = $request->request->get('mdp');
        $user = $em->getRepository(User::class)->find($id);
        $motdepasse = $encoder->encodePassword($user, $mdp);

        if (!$user) {
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
    public function editEmail(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('id');
        $email = $request->request->get('email');
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Erreur.');
        }
        $user->setEmail($email);
        $em->flush();

        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/ajout_ami", name="ajout_ami")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function ajoutAmis(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idJoueur = $this->getUser()->getId();
        $joueur = $em->getRepository(User::class)->find($idJoueur);
        $idAmi = $request->request->get('ami');
        $getAmi = $em->getRepository(User::class)->find($idAmi);
        $amiRep = $em->getRepository(Ami::class)->checkIfFriends($idJoueur, $idAmi);

        if ($amiRep != null) {
            if ($amiRep->getStatut() == 1) {
                $this->addFlash('notice_ajout', 'Vous êtes déjà ami avec cette personne.');
                return $this->redirectToRoute('profil');
            } else if ($amiRep->getStatut() == 0 && $amiRep->getDerniereAction() == $idJoueur) {
                $this->addFlash('notice_ajout', 'Vous avez déjà demandé cette personne en ami');
                return $this->redirectToRoute('profil');
            } else if ($amiRep->getStatut() == 0 && $amiRep->getDerniereAction() == $idAmi) {
                $this->addFlash('notice_ajout', 'Cette personne vous a demandé en ami. Allez l\'accepter !');
                return $this->redirectToRoute('profil');
            }
        } else if ($amiRep == null) {
            $ami = new Ami();
            $ami->setJ1($joueur);
            $ami->setJ2($getAmi);
            $ami->setStatut(0);
            $ami->setDerniereAction($idJoueur);
            $em->persist($ami);
            $em->flush();
            return $this->redirectToRoute('profil');
        }

        $this->addFlash('notice_ajout', 'Erreur.');
        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/repondre_demande", name="repondre_demande")
     * @param Request $request
     * @return Response
     */
    public function repondreDemandeAmi(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idJoueur = $this->getUser()->getId();
        $idAmi = $request->request->get('ami_id');
        $amiRep = $em->getRepository(Ami::class)->checkIfFriends($idJoueur, $idAmi);
        $accepter = $request->request->get('accepter');
        $refuser = $request->request->get('refuser');
        if ($accepter) {

            $amiRep->setStatut(1);
            $em->flush();
            return $this->redirectToRoute('profil');
        }
        if ($refuser) {
            $em->remove($amiRep);
            $em->flush();

            return $this->redirectToRoute('profil');
        }
        $this->addFlash('notice_amis', 'Erreur.');
        return $this->redirectToRoute('profil');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/supprimer_ami", name="supprimer_ami")
     */
    public function supprimerAmi(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $idJoueur = $this->getUser()->getId();
        $idAmi = $request->request->get('ami_id');
        $amiRep = $em->getRepository(Ami::class)->checkIfFriends($idJoueur, $idAmi);
        $supprimer = $request->request->get('supprimer');
        $annuler = $request->request->get('annuler');
        if($amiRep) {
            $em->remove($amiRep);
            $em->flush();
        }
        else {
            $this->addFlash('notice_amis', 'Vous n\'êtes pas amis.');
            return $this->redirectToRoute('profil');
        }
        if($annuler) {
            $this->addFlash('notice_amis', 'Votre demande a été annulée.');
            return $this->redirectToRoute('profil');
        } elseif($supprimer) {
            $this->addFlash('notice_amis', 'Cet ami a été supprimé.');
            return $this->redirectToRoute('profil');
        }

        return $this->redirectToRoute('profil');
    }

    /**
     * @Route("/mdp_oublie", name="mdp_oublie")
     */
    public function mdpOublie() {
        return $this->render('Profil/mdpoublie.html.twig');
    }

    /**
     * @Route("/envoi_mail", name="envoi_mail")
     */
    public function sendMail( Request $request) {
        $email = $request->request->get('email');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

        $transport = (new \Swift_SmtpTransport('smtps.univ-reims.fr', 465))
            ->setUsername('nicolas.berthou@etudiant.univ-reims.fr')
            ->setPassword('141AxWa6');
        $mailer = new \Swift_Mailer($transport);
        $message = (new \Swift_Message('Mot de passe oublié') )
            ->setFrom(['contact@agencepkp.fr' => 'Agence PKP'])
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'Emails/oublimdp.html.twig',
                    ['email' => $email, 'user' => $user]
                )
            );
        $mailer->send($message);
        return $this->redirectToRoute('connexion');
    }

    /**
     * @Route("/recup_mdp", name="recup_mdp")
     */
    public function recupMdp() {
        return $this->render('index.html.twig');
    }
}