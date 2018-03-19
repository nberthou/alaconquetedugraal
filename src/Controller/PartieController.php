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
       $amis = $this->getDoctrine()->getManager()->getRepository("App:Ami")->AjoutFindBy($userId, 1);
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
       $joueur = $this->getDoctrine()->getRepository(User::class)->find($idJoueur);
       $objectifs = $this->getDoctrine()->getRepository("App:Objectif")->findAll();
       $adversaire = $this->getDoctrine()->getRepository(User::class)->find($idAdversaire);

       $cartes = $this->getDoctrine()->getRepository(Carte::class)->findAll();
       $tCartes = array();

       foreach($cartes as $carte) {
           $tCartes[] = $carte->getId();
       }

       $tObjectifs = array();
       foreach($objectifs as $objectif) {
           $tObjectifs[] = $objectif->getId();
       }

       shuffle($tCartes);

       $cartejetee = array_pop($tCartes);

       $tMainJ1 = array();
       for($i = 0 ; $i < 6; $i++) {
           $tMainJ1[] = array_pop($tCartes);
       }

       $tMainJ2 = array();
       for($i = 0 ; $i < 6; $i++) {
           $tMainJ2[] = array_pop($tCartes);
       }

       $pioche = $tCartes;

       $partie = new Partie();
       $partie->setJ1($joueur);
       $partie->setJ2($adversaire);
       $partie->setCarteJetee($cartejetee);
       $partie->setMainJ1(json_encode($tMainJ1));
       $partie->setMainJ2(json_encode($tMainJ2));
       $partie->setPioche(json_encode($pioche));
       $partie->setScoreJ1(0);
       $partie->setScoreJ2(0);
       $partie->setTour($joueur);
       $partie->setManche(1);
       $partie->setObjectifs(json_encode($tObjectifs));
       $partie->setActionJ1('[1, 2, 3, 4]');
       $partie->setActionJ2('[1, 2, 3, 4]');
       $partie->setTerrainJ1('[]');
       $partie->setTerrainJ2('[]');
       $partie->setEtat(0);
       $partie->setDebut();
       $em = $this->getDoctrine()->getManager();
       $em->persist($partie);
       $em->flush();
       return $this->redirectToRoute("attente_partie", ['id' => $partie->getId()]);
   }

    /**
     * @Route("/{id}/attente_partie", name="attente_partie")
     * @param Partie $partie
     */
   public function attendrePartie(Partie $partie) {
       $etat = $partie->getEtat();

       if($etat == 0) {
           return $this->render('Partie/attente.html.twig');
       }
       if($etat == 1) {
           return $this->redirectToRoute('afficher_partie', ['id' => $partie->getId(), 'partie' => $partie]);
       }

       return $this->render('Partie/attente.html.twig');
   }

    /**
     * @Route("/{id}", name="afficher_partie")
     * @param Partie $partie
     */
    public function afficherPartie(Partie $partie) {
        $cartes = $this->getDoctrine()->getRepository("App:Carte")->findAll();
        $objectifs = $this->getDoctrine()->getRepository("App:Objectif")->findAll();
        $tCartes = array();
        foreach($cartes as $carte) {
            $tCartes[$carte->getId()] = $carte;
        }
        return $this->render('partie/afficher.html.twig', ['partie' => $partie, 'cartes' => $tCartes, 'objectifs' => $objectifs, 'id' => $partie->getId()]);
    }
    /**
     * @Route("/{id}/changement_tour", name="changement_tour")
     */
    public function changerTour() {
    }
}