<?php
/**
 * Created by PhpStorm.
 * User2: Nicolas
 * Date: 20/02/2018
 * Time: 09:06
 */

namespace App\Controller;


use App\Entity\Carte;
use App\Entity\Partie;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/partie")
 * Class PartieController
 * @package App\Controller
 */
class PartieController extends Controller
{


    /**
     * @Route("/recherche", name="recherche_joueurs")
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function rechercheJoueurs() {
       $userId = $this->getUser()->getId();
       $amis = $this->getDoctrine()->getManager()->getRepository(Ami::class)->AjoutFindBy($userId, 1);
       $joueurs = $this->getDoctrine()->getRepository(User::class)->findAll();
       return $this->render('partie/recherche.html.twig', ['joueurs' => $joueurs, 'amis' => $amis]);
   }

    /**
     * @Route("/creer", name="creer_partie")
     * @param Request $request
     * @return Response
     */
   public function creerParties(Request $request) {
       $idAdversaire = $request->request->get('adversaire');
       $idJoueur = $this->getUser()->getId();
       $joueur = $this->getDoctrine()->getManager()->getRepository(User::class)->find($idJoueur);
       $adversaire = $this->getDoctrine()->getManager()->getRepository(User::class)->find($idAdversaire);

       $cartes = $this->getDoctrine()->getRepository(Carte::class)->findAll();
       $tCartes = array();

       foreach($cartes as $carte) {
           $tCartes[] = $carte->getId();
       }

       shuffle($tCartes);

       $cartejetee = array_pop($tCartes);

       $mainJ1 = array();
       for($i = 0 ; $i < 6, $i++;) {
           $mainJ1[] = array_pop($tCartes);
       }

       $mainJ2 = array();
       for($i = 0 ; $i < 6, $i++;) {
           $mainJ2[] = array_pop($tCartes);
       }

       $pioche = $tCartes;

       $partie = new Partie();
       $partie->setJ1($joueur);
       $partie->setJ2($adversaire);
       $partie->setCarteJetee($cartejetee);
       $partie->setMainJ1(json_encode($mainJ1));
       $partie->setMainJ2(json_encode($mainJ2));
       $partie->setPioche(json_encode($pioche));
       $partie->setScoreJ1(0);
       $partie->setScoreJ2(0);
       $partie->setTour(1);
       $partie->setManche(1);

       $em = $this->getDoctrine()->getManager();

       $em->persist($partie);
       $em->flush();

       return $this->render('partie/creer.html.twig');
   }

    /**
     * @Route("/{id}", name="afficher_partie")
     * @param Partie $partie
     */
   public function afficherPartie(Partie $partie) {
       return $this->render('partie/afficher.html.twig', ['partie' => $partie]);
   }
}