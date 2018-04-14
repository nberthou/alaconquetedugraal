<?php

namespace App\Controller;


use App\Entity\Carte;
use App\Entity\Objectif;
use App\Entity\Partie;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function rechercheJoueurs()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $userId = $this->getUser()->getId();
        $joueurs = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('partie/recherche.html.twig', ['joueurs' => $joueurs]);
    }

    /**
     *
     * /**
     * @Route("/creer", name="creer_partie")
     * @param Request $request
     * @return Response
     */
    public function creerPartie(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $idAdversaire = $request->request->get('adversaire');
        $idJoueur = $this->getUser()->getId();
        $joueur = $this->getDoctrine()->getRepository(User::class)->find($idJoueur);
        $adversaire = $this->getDoctrine()->getRepository(User::class)->find($idAdversaire);
        $objectifs = $this->getDoctrine()->getRepository(Objectif::class)->findAll();
        $cartes = $this->getDoctrine()->getRepository(Carte::class)->findAll();


        $partie = new Partie();

        $tCartes = array();
        $tObjectifs = array();
        $tActions = array();

        foreach($objectifs as $objectif) {
            $tObjectifs["obj".$objectif->getId()] =  0;
        }

        foreach($cartes as $carte) {
            $tCartes[] = $carte->getId();
        }



        $secret = array('etat' => 0, 'carte' => 0);
        $diss = array('etat' => 0, 'cartes' => array());
        $cadeau = array('etat' => 0, 'cartes' => array());
        $concurrence = array('etat' => 0, 'cartes' => array("paire1" => array(), "paire2" => array()));
        $tActions = array('secret' => $secret, 'dissimulation' => $diss, 'cadeau' => $cadeau, 'concurrence' => $concurrence);

        shuffle($tCartes);

        $cartejetee = array_pop($tCartes);


        $tMainJ1 = array();
        for($i=0; $i<6; $i++) {
            $tMainJ1[] = array_pop($tCartes);
        }
        $tMainJ2 = array();
        for($i=0; $i<6; $i++) {
            $tMainJ2[] = array_pop($tCartes);
        }


        $random = rand(1,2);

        if($random == 1) {
            $partie->setTour($joueur);
            $tMainJ1[] = array_pop($tCartes);
        } else {
            $partie->setTour($adversaire);
            $tMainJ2[] = array_pop($tCartes);
        }

        sort($tMainJ1);
        sort($tMainJ2);

        $terrainJ1 = array();
        $terrainJ2 = array();

        $pioche = $tCartes;

        $partie->setJ1($joueur);
        $partie->setJ2($adversaire);
        $joueur->setPartiesJouees($joueur->getPartiesJouees() + 1);
        $adversaire->setPartiesJouees($adversaire->getPartiesJouees() + 1);
        $partie->setMainJ1(json_encode($tMainJ1));
        $partie->setMainJ2(json_encode($tMainJ2));
        $partie->setPioche(json_encode($pioche));
        $partie->setScoreJ1(0);
        $partie->setEtat(0);
        $partie->setScoreJ2(0);
        $partie->setManche(1);
        $joueur->setPartiesJouees($joueur->getPartiesJouees() + 1 );
        $adversaire->setPartiesJouees($adversaire->getPartiesJouees() +1);
        $partie->setObjectifs(json_encode($tObjectifs));
        $partie->setCarteJetee($cartejetee);
        $partie->setActionJ1(json_encode($tActions));
        $partie->setActionJ2(json_encode($tActions));
        $partie->setTerrainJ1(json_encode($terrainJ1));
        $partie->setTerrainJ2(json_encode($terrainJ2));

        $em = $this->getDoctrine()->getManager();

        $em->persist($partie);
        $em->flush();

        return $this->redirectToRoute("afficher_partie", ['id' => $partie->getId(), 'partie' => $partie]);
    }

    /**
     * @Route("/{id}", name="afficher_partie")
     * @param Partie $partie
     */
    public function afficherPartie(Partie $partie) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $cartes = $this->getDoctrine()->getRepository("App:Carte")->findAll();
        $objectifs = $this->getDoctrine()->getRepository("App:Objectif")->findAll();

        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur');
        $tActionsA = ($adversaire == $partie->getJ1()) ? $partie->getActionJ1() : (($adversaire == $partie->getJ2()) ? $partie->getActionJ2() : 'Erreur');
        $tActionsJ = ($joueur == $partie->getJ1()) ? $partie->getActionJ1() : (($joueur == $partie->getJ2()) ? $partie->getActionJ2() : 'Erreur');

        $actionsJ = array_column($tActionsJ, 'etat');
        $actionsA = array_column($tActionsA, 'etat');

        $tCartes = array();
        foreach($cartes as $carte) {
            $tCartes[$carte->getId()] = $carte;
        }


        return $this->render('Partie/afficher.html.twig', ['partie' => $partie, 'cartes' => $tCartes, 'objectifs' => $objectifs, 'id' => $partie, 'actionsJ' => $actionsJ, 'actionsA' => $actionsA]);
    }

    /**
     * @Route("/{id}/tour_suivant", name="tour_suivant")
     */
    public function tourSuivant(Partie $partie)
    {
        $joueur = $this->getUser();
        $adversaire =  ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ?  $partie->getJ1() : 'Erreur');

        $tMainJ1 = $partie->getMainJ1();
        $tMainJ2 = $partie->getMainJ2();
        $pioche = $partie->getPioche();
        $actionsJ1 = $partie->getActionJ1();
        $actionsJ2 = $partie->getActionJ2();

        $cartePioche = array_pop($pioche);


        if ($joueur == $partie->getJ1()) {
            $tMainJ2[] = $cartePioche;
            sort($tMainJ2);
            $partie->setMainJ2(json_encode($tMainJ2));
        } else {
            if ($joueur == $partie->getJ2()) {
                $tMainJ1[] = $cartePioche;
                sort($tMainJ1);
                $partie->setMainJ1(json_encode($tMainJ1));
            }
        }

        $partie->setPioche(json_encode($pioche));

        if($partie->getTour() == $joueur) {
            $partie->setTour($adversaire);
        }
        else if($partie->getTour() == $adversaire) {
            $partie->setTour($joueur);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
    }

    /**
     * @Route("/{id}/secret", name="secret")
     */
    public function secret(Partie $partie, Request $request)
    {
        $joueur = $this->getUser();

        $carteSecretId = $request->request->get('secret');
        $tMain =  ($joueur == $partie->getJ1()) ? $partie->getMainJ1() : (($joueur == $partie->getJ2()) ? $partie->getMainJ2() : 'Erreur');
        $tActions = ($joueur == $partie->getJ1()) ? $partie->getActionJ1() : (($joueur == $partie->getJ2()) ? $partie->getActionJ2() : 'Erreur');
        unset($tMain[array_search($carteSecretId, $tMain)]);
        $ntMain = array_values($tMain);
        sort($ntMain);
        $tActions['secret']->etat = 1;
        ($joueur == $partie->getJ1()) ? $partie->setMainJ1(json_encode($ntMain)) : (($joueur == $partie->getJ2()) ? $partie->setMainJ2(json_encode($ntMain)) : 'Erreur');
        ($joueur == $partie->getJ1()) ? $partie->setActionJ1(json_encode($tActions)) : (($joueur == $partie->getJ2()) ? $partie->setActionJ2(json_encode($tActions)) : 'Erreur');
        $this->tourSuivant($partie);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
    }

    /**
     * @Route("/{id}/dissimulation", name="dissimulation")
     * @param Partie $partie
     * @param Request $request
     */
    public function dissimulation(Partie $partie, Request $request)
    {
        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : ($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur';
        $carteDissId1 = $request->request->get('carte1');
        $carteDissId2 = $request->request->get('carte2');
        $carteDiss1 = $this->getDoctrine()->getRepository(Carte::class)->find($carteDissId1);
        $carteDiss2 = $this->getDoctrine()->getRepository(Carte::class)->find($carteDissId2);
        $tMain = ($joueur == $partie->getJ1()) ? $partie->getMainJ1() : (($joueur == $partie->getJ2(
            )) ? $partie->getMainJ2() : 'Erreur');
        $tActions = ($joueur == $partie->getJ1()) ? $partie->getActionJ1() : (($joueur == $partie->getJ2(
            )) ? $partie->getActionJ2() : 'Erreur');

        if ($carteDissId1 == null || $carteDissId2 == null) {
            return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
        } else {
            unset($tMain[array_search($carteDissId1, $tMain)]);
            unset($tMain[array_search($carteDissId2, $tMain)]);

            $ntMain = array_values($tMain);

            sort($ntMain);

            $tActions['dissimulation']->etat = 1;
            $tActions['dissimulation']->cartes = array($carteDissId1, $carteDissId2);

            ($joueur == $partie->getJ1()) ? $partie->setMainJ1(json_encode($ntMain)) : (($joueur == $partie->getJ2(
                )) ? $partie->setMainJ2(json_encode($ntMain)) : 'Erreur');
            ($joueur == $partie->getJ1()) ? $partie->setActionJ1(json_encode($tActions)) : (($joueur == $partie->getJ2(
                )) ? $partie->setActionJ2(json_encode($tActions)) : 'Erreur');
            $this->tourSuivant($partie);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
        }
    }

    /**
     * @Route("/{id}/don_cadeau", name="don_cadeau")
     * @param Partie $partie
     * @param Request $request
     */
    public function donnerCadeau(Partie $partie, Request $request)
    {
        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur');
        $carteCadeau1 = $request->request->get('cartec1');
        $carteCadeau2 = $request->request->get('cartec2');
        $carteCadeau3 = $request->request->get('cartec3');
        $carte1 = $this->getDoctrine()->getRepository(Carte::class)->find($carteCadeau1)->getImg();
        $carte2 = $this->getDoctrine()->getRepository(Carte::class)->find($carteCadeau2)->getImg();
        $carte3 = $this->getDoctrine()->getRepository(Carte::class)->find($carteCadeau3)->getImg();
        $tMain = ($joueur == $partie->getJ1()) ? $partie->getMainJ1() : (($joueur == $partie->getJ2()) ? $partie->getMainJ2() : 'Erreur');
        $tActions = ($joueur == $partie->getJ1()) ? $partie->getActionJ1() : (($joueur == $partie->getJ2()) ? $partie->getActionJ2() : 'Erreur');

        unset($tMain[array_search($carteCadeau1, $tMain)]);
        unset($tMain[array_search($carteCadeau2, $tMain)]);
        unset($tMain[array_search($carteCadeau3, $tMain)]);
        $tMain = array_values($tMain);
        sort($tMain);

        $tActions['cadeau']->etat = 1;
        $tActions['cadeau']->cartes = array($carteCadeau1 => $carte1, $carteCadeau2 => $carte2, $carteCadeau3 => $carte3);

        ($joueur == $partie->getJ1()) ? $partie->setMainJ1(json_encode($tMain)) : (($joueur == $partie->getJ2()) ? $partie->setMainJ2(json_encode($tMain)) : 'Erreur');
        ($joueur == $partie->getJ1()) ? $partie->setActionJ1(json_encode($tActions)) : (($joueur == $partie->getJ2()) ? $partie->setActionJ2(json_encode($tActions)) : 'Erreur');

        $this->tourSuivant($partie);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
    }

    /**
     * @Route("/{id}/recu_cadeau", name="recu_cadeau")
     * @param Partie $partie
     * @param Request $request
     */
    public function recevoirCadeau(Partie $partie, Request $request)
    {
        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur');
        $carteRecue = $request->request->get('carter');
        $tCartes = ($joueur == $partie->getJ1()) ? $partie->getActionJ2()['cadeau']->cartes : (($joueur == $partie->getJ2()) ? $partie->getActionJ1()['cadeau']->cartes : 'Erreur');
        $tActions = ($adversaire == $partie->getJ1()) ? $partie->getActionJ1() : (($adversaire == $partie->getJ2()) ? $partie->getActionJ2() : 'Erreur');
        $terrainJ = ($joueur == $partie->getJ1()) ? $partie->getTerrainJ1() : (($joueur == $partie->getJ2()) ? $partie->getTerrainJ2() : 'Erreur');
        $terrainA = ($adversaire == $partie->getJ1()) ? $partie->getTerrainJ1() : (($adversaire == $partie->getJ2()) ? $partie->getTerrainJ2() : 'Erreur');

        $tCartesKeys = array();
        foreach($tCartes as $tCarte => $value) {
            $tCartesKeys[] = ($tCarte);
        }

        $tCartes =  json_encode($tCartes);
        $tCartes = json_decode($tCartes, true);

        $ouais = $tCartesKeys[array_search($carteRecue, array_values($tCartesKeys))];

        unset($tCartes[$ouais]);

        $tCartesAdverses = array_keys($tCartes);
        $tCartes = array();
        $terrainJ[] = json_decode($carteRecue);

        $terrainA[] = json_decode($tCartesAdverses[0]); $terrainA[] = json_decode($tCartesAdverses[1]);

        $tActions['cadeau']->etat = 2;
        $tActions['cadeau']->cartes = array();
        ($joueur == $partie->getJ1()) ? $partie->setTerrainJ1(json_encode($terrainJ)) : (($joueur == $partie->getJ2()) ? $partie->setTerrainJ2(json_encode($terrainJ)) : 'Erreur');
        ($adversaire == $partie->getJ1()) ? $partie->setTerrainJ1(json_encode($terrainA)) : (($adversaire == $partie->getJ2()) ? $partie->setTerrainJ2(json_encode($terrainA)) : 'Erreur');
        ($adversaire == $partie->getJ1()) ? $partie->setActionJ1(json_encode($tActions)) : (($adversaire == $partie->getJ2()) ? $partie->setActionJ2(json_encode($tActions)) : 'Erreur');

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
    }

    /**
     * @Route("/{id}/don_concurrence", name="don_concurrence")
     * @param Request $request
     * @param Partie $partie
     */
    public function donnerConcurrence(Request $request, Partie $partie)
    {
        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur');
        $carte1_1 = $request->request->get('carte1_1');
        $carte1_2 = $request->request->get('carte1_2');
        $carte2_1 = $request->request->get('carte2_1');
        $carte2_2 = $request->request->get('carte2_2');
        $cartesP1 = array($carte1_1, $carte1_2);
        $cartesP2 = array($carte2_1, $carte2_2);

        $tMain = ($joueur == $partie->getJ1()) ? $partie->getMainJ1() : (($joueur == $partie->getJ2()) ? $partie->getMainJ2() : 'Erreur');
        $tActions = ($joueur == $partie->getJ1()) ? $partie->getActionJ1() : (($joueur == $partie->getJ2()) ? $partie->getActionJ2() : 'Erreur');

        unset($tMain[array_search($carte1_1, $tMain)]);
        unset($tMain[array_search($carte1_2, $tMain)]);
        unset($tMain[array_search($carte2_1, $tMain)]);
        unset($tMain[array_search($carte2_2, $tMain)]);

        $tMain = array_values($tMain);
        sort($tMain);

        $tActions['concurrence']->cartes->paire1 = $cartesP1;
        $tActions['concurrence']->cartes->paire2 = $cartesP2;
        $tActions['concurrence']->etat = 1;

        ($joueur == $partie->getJ1()) ? $partie->setMainJ1(json_encode($tMain)) : (($joueur == $partie->getJ2()) ? $partie->setMainJ2(json_encode($tMain)) : 'Erreur');
        ($joueur == $partie->getJ1()) ? $partie->setActionJ1(json_encode($tActions)) : (($joueur == $partie->getJ2()) ? $partie->setActionJ2(json_encode($tActions)) : 'Erreur');

        $this->tourSuivant($partie);

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
    }

    /**
     * @Route("/{id}/recu_concurrence", name="recu_concurrence")
     * @param Partie $partie
     * @param Request $request
     */
    public function recevoirConcurrence(Partie $partie, Request $request)
    {
        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur');
        $paire1 = $request->request->get('paire1');
        $paire2 = $request->request->get('paire2');
        $tCartes = ($joueur == $partie->getJ1()) ? $partie->getActionJ2()['concurrence']->cartes : (($joueur == $partie->getJ2()) ? $partie->getActionJ1()['concurrence']->cartes : 'Erreur');
        $tPaire1 = ($joueur == $partie->getJ1()) ? $partie->getActionJ2()['concurrence']->cartes->paire1 : (($joueur == $partie->getJ2()) ? $partie->getActionJ1()['concurrence']->cartes->paire1 : 'Erreur');
        $tPaire2 = ($joueur == $partie->getJ1()) ? $partie->getActionJ2()['concurrence']->cartes->paire2 : (($joueur == $partie->getJ2()) ? $partie->getActionJ1()['concurrence']->cartes->paire2 : 'Erreur');
        $tActions = ($adversaire == $partie->getJ1()) ? $partie->getActionJ1() : (($adversaire == $partie->getJ2()) ? $partie->getActionJ2() : 'Erreur');
        $terrainJ = ($joueur == $partie->getJ1()) ? $partie->getTerrainJ1() : (($joueur == $partie->getJ2()) ? $partie->getTerrainJ2() : 'Erreur');
        $terrainA = ($adversaire == $partie->getJ1()) ? $partie->getTerrainJ1() : (($adversaire == $partie->getJ2()) ? $partie->getTerrainJ2() : 'Erreur');

        $cartesR = array("paire1" => array(), 'paire2' => array());


        $tCartes = (array) $tCartes;
        if($paire1) {
            unset($tCartes[array_search($tPaire1, $tCartes)]);
            $tCartes = array_values($tCartes);
            $tCartesAdversaire = $tCartes;
            $tCartes = array();
            $terrainJ[] = json_decode($tPaire1[0]); $terrainJ[] = json_decode($tPaire1[1]);
            $terrainA[] = json_decode($tPaire2[0]); $terrainA[] = json_decode($tPaire2[1]);
        } else if($paire2) {
            unset($tCartes[array_search($tPaire2,  $tCartes)]);
            $tCartes = array_values($tCartes);
            $tCartesAdversaire = $tCartes;
            $tCartes = array();
            $terrainJ[] = json_decode($tPaire2[0]); $terrainJ[] = json_decode($tPaire2[1]);
            $terrainA[] = json_decode($tPaire1[0]); $terrainA[] = json_decode($tPaire1[1]);
        }


        $tActions['concurrence']->etat = 2;

        ($joueur == $partie->getJ1()) ? $partie->setTerrainJ1(json_encode($terrainJ)) : (($joueur == $partie->getJ2()) ? $partie->setTerrainJ2(json_encode($terrainJ)) : 'Erreur');
        ($adversaire == $partie->getJ1()) ? $partie->setTerrainJ1(json_encode($terrainA)) : (($adversaire == $partie->getJ2()) ? $partie->setTerrainJ2(json_encode($terrainA)) : 'Erreur');
        ($adversaire == $partie->getJ1()) ? $partie->setActionJ1(json_encode($tActions)) : (($adversaire == $partie->getJ2()) ? $partie->setActionJ2(json_encode($tActions)) : 'Erreur');

        $this->getDoctrine()->getManager()->flush();


        return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
    }

    /**
     * @Route("/{id}/fin_manche", name="fin_manche")
     * @param Request $request
     * @param Partie $partie
     */
    public function finManche(Request $request, Partie $partie)
    {
        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur');
        $terrainJ = ($joueur == $partie->getJ1()) ? $partie->getTerrainJ1() : (($joueur == $partie->getJ2()) ? $partie->getTerrainJ2() : 'Erreur');
        $terrainA = ($adversaire == $partie->getJ1()) ? $partie->getTerrainJ1() : (($adversaire == $partie->getJ2()) ? $partie->getTerrainJ2() : 'Erreur');
        $scoreJ = ($joueur == $partie->getJ1()) ? $partie->getScoreJ1() : (($joueur == $partie->getJ2()) ? $partie->getScoreJ2() : 'Erreur');
        $scoreA = ($adversaire == $partie->getJ1()) ? $partie->getScoreJ1() : (($adversaire == $partie->getJ2()) ? $partie->getScoreJ2() : 'Erreur');
        $objectifs = $partie->getObjectifs();
        $cartes = $this->getDoctrine()->getRepository(Carte::class)->findAll();
        $cartesSJ = array();
        $cartesSA = array();
        $cartesOJ = array();
        $cartesOA = array();

        $terrainJ[] = ($joueur == $partie->getJ1()) ? $partie->getActionJ1()['secret']->carte : (($joueur == $partie->getJ2()) ? $partie->getActionJ2()['secret']->carte : 'Erreur');
        $terrainA[] = ($adversaire == $partie->getJ1()) ? $partie->getActionJ1()['secret']->carte : (($adversaire == $partie->getJ2()) ? $partie->getActionJ2()['secret']->carte : 'Erreur');

        array_pop($terrainJ); array_pop($terrainA);
        foreach($terrainJ as $cartesIdJ) {
            $cartesSJ[] = $this->getDoctrine()->getRepository(Carte::class)->find($cartesIdJ)->getPoints();
            $cartesOJ[] = $this->getDoctrine()->getRepository(Carte::class)->find($cartesIdJ)->getObjectif()->getId();
        }

        foreach($terrainA as $cartesIdA) {
            $cartesSA[] = $this->getDoctrine()->getRepository(Carte::class)->find($cartesIdA)->getPoints();
            $cartesOA[] = $this->getDoctrine()->getRepository(Carte::class)->find($cartesIdA)->getObjectif()->getId();
        }
        for($i = 2 ; $i < 6; $i++) {
            if(!isset(array_count_values($cartesSJ)[$i]))
            {
                ($adversaire == $partie->getJ1()) ? $partie->setScoreJ1($partie->getScoreJ1() + $i) : (($adversaire == $partie->getJ2()) ? $partie->setScoreJ2($partie->getScoreJ2() + $i) : 'Erreur');
            }
            else if(isset(array_count_values($cartesSJ)[$i]) && !isset(array_count_values($cartesSA)[$i])) {
                ($joueur == $partie->getJ1()) ? $partie->setScoreJ1($partie->getScoreJ1() + $i) : (($joueur == $partie->getJ2()) ? $partie->setScoreJ2($partie->getScoreJ2() + $i) : 'Erreur');
            }
            else if(array_count_values($cartesSJ)[$i] >= 1 &&  array_count_values($cartesSJ)[$i] > array_count_values($cartesSA)[$i]) {
                ($joueur == $partie->getJ1()) ? $partie->setScoreJ1($partie->getScoreJ1() + $i) : (($joueur == $partie->getJ2()) ? $partie->setScoreJ2($partie->getScoreJ2() + $i) : 'Erreur');
            }
            else if(array_count_values($cartesSJ)[$i] >= 1 &&  array_count_values($cartesSJ)[$i] == array_count_values($cartesSA)[$i] ) {
            }
            else {
                ($adversaire == $partie->getJ1()) ? $partie->setScoreJ1($partie->getScoreJ1() + $i) : (($adversaire == $partie->getJ2()) ? $partie->setScoreJ2($partie->getScoreJ2() + $i) : 'Erreur');
            }
        }
        for($i = 1; $i < 8; $i++) {
            if (!isset(array_count_values($cartesOJ)[$i]) and !isset(array_count_values($cartesOA)[$i])) {
            } else if (!isset(array_count_values($cartesOJ)[$i]) and isset(array_count_values($cartesOA)[$i])) {
                ($adversaire == $partie->getJ1()) ? $objectifs["obj".$i] = 1 : (($adversaire == $partie->getJ2()) ? $objectifs["obj".$i] = 2 : 'Erreur');
                $partie->setObjectifs($objectifs);
            } else if (isset(array_count_values($cartesOJ)[$i]) and !isset(array_count_values($cartesOA)[$i])) {
                ($joueur == $partie->getJ1()) ? $objectifs["obj".$i] = 1 : (($joueur == $partie->getJ2()) ? $objectifs["obj".$i] = 2 : 'Erreur');
                $partie->setObjectifs($objectifs);
            } else if (array_count_values($cartesOJ)[$i] >= 1 && array_count_values($cartesOJ)[$i] > array_count_values($cartesOA)[$i]) {

                ($joueur == $partie->getJ1()) ? $objectifs["obj".$i] = 1 : (($joueur == $partie->getJ2()) ? $objectifs["obj".$i] = 2 : 'Erreur');
                $partie->setObjectifs($objectifs);
            } else if (array_count_values($cartesOJ)[$i] >= 1 && array_count_values($cartesOJ)[$i] == array_count_values($cartesOA)[$i]) {
            } else {
                ($adversaire == $partie->getJ1()) ? $objectifs["obj".$i] = 1 : (($adversaire == $partie->getJ2()) ? $objectifs["obj".$i] = 2 : 'Erreur');
                $partie->setObjectifs($objectifs);
            }
        }

        if($partie->getScoreJ1() == 11 || $partie->getScoreJ2() == 11 || array_count_values(array_values($objectifs))[1] >= 4 || array_count_values(array_values($objectifs))[2] >= 4) {
            if(array_count_values(array_values($objectifs))[1] >= 4 && $partie->getScoreJ2() == 11) {
                $gagnant = $partie->getJ1(); $perdant = $partie->getJ2();
            } else if(array_count_values(array_values($objectifs))[2] >= 4 && $partie->getScoreJ1() == 11) {
                $gagnant = $partie->getJ2(); $perdant = $partie->getJ1();
            } else if($partie->getScoreJ1() == 11) {
                $gagnant = $partie->getJ1(); $perdant = $partie->getJ2();
            } else if($partie->getScoreJ2() == 11) {
                $gagnant = $partie->getJ2(); $perdant = $partie->getJ1();
            } else if(array_count_values(array_values($objectifs))[1] >= 4) {
                $gagnant =  $partie->getJ1(); $perdant = $partie->getJ2();
            } else if(array_count_values(array_values($objectifs))[2] >= 4) {
                $gagnant = $partie->getJ2(); $perdant = $partie->getJ1();
            }
            $partie->setEtat(1);
            $gagnant->setPartiesGagnees($gagnant->getPartiesGagnees() + 1);
            $scoreG = ($gagnant == $partie->getJ1()) ? $partie->getScoreJ1() : (($gagnant == $partie->getJ2()) ? $partie->getScoreJ2() : 'Erreur' );
            $scoreP = ($perdant == $partie->getJ1()) ? $partie->getScoreJ1() : (($perdant == $partie->getJ2()) ? $partie->getScoreJ2() : 'Erreur' );
            $objectifsG = ($gagnant == $partie->getJ1()) ? array_count_values(array_values($objectifs))[1] : (($gagnant == $partie->getJ2()) ? array_count_values(array_values($objectifs))[2] : 'Erreur');
            $objectifsP = ($perdant == $partie->getJ1()) ? array_count_values(array_values($objectifs))[1] : (($perdant == $partie->getJ2()) ? array_count_values(array_values($objectifs))[2] : 'Erreur');
            $partie->setObjectifs(json_encode($objectifs));
            $this->getDoctrine()->getManager()->flush();

            return $this->render('Partie/fin.html.twig', ['id' => $partie->getId(), 'partie' => $partie, 'gagnant' => $gagnant, 'scoreG' => $scoreG, 'objectifsG' => $objectifsG, 'perdant' => $perdant, 'scoreP' => $scoreP, 'objectifsP' => $objectifsP]);

        } else {
            $tCartes = array();
            $tActions = array();

            foreach($cartes as $carte) {
                $tCartes[] = $carte->getId();
            }
            $secret = array('etat' => 0, 'carte' => 0);
            $diss = array('etat' => 0, 'cartes' => array());
            $cadeau = array('etat' => 0, 'cartes' => array());
            $concurrence = array('etat' => 0, 'cartes' => array("paire1" => array(), "paire2" => array()));
            $tActions = array('secret' => $secret, 'dissimulation' => $diss, 'cadeau' => $cadeau, 'concurrence' => $concurrence);
            shuffle($tCartes);
            $cartejetee = array_pop($tCartes);
            $tMainJ1 = array();
            for($i=0; $i<6; $i++) {
                $tMainJ1[] = array_pop($tCartes);
            }
            $tMainJ2 = array();
            for($i=0; $i<6; $i++) {
                $tMainJ2[] = array_pop($tCartes);
            }
            $random = rand(1,2);
            if($random == 1) {
                $partie->setTour($joueur);
                $tMainJ1[] = array_pop($tCartes);
            } else {
                $partie->setTour($adversaire);
                $tMainJ2[] = array_pop($tCartes);
            }
            sort($tMainJ1);
            sort($tMainJ2);
            $terrainJ1 = array();
            $terrainJ2 = array();
            $pioche = $tCartes;

            $partie->setMainJ1(json_encode($tMainJ1));
            $partie->setMainJ2(json_encode($tMainJ2));
            $partie->setPioche(json_encode($pioche));
            $partie->setScoreJ1(0);
            $partie->setScoreJ2(0);
            $partie->setManche($partie->getManche() + 1);
            $partie->setCarteJetee($cartejetee);
            $partie->setObjectifs(json_encode($objectifs));
            $partie->setActionJ1(json_encode($tActions));
            $partie->setActionJ2(json_encode($tActions));
            $partie->setTerrainJ1(json_encode($terrainJ1));
            $partie->setTerrainJ2(json_encode($terrainJ2));
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
        }
        return $this->redirectToRoute('afficher_partie', ['partie' => $partie, 'id' => $partie->getId()]);
    }


    /**
     * @Route("/{id}/plateau", name="plateau")
     * @param Partie $partie
     */
    public function plateau(Partie $partie ) {
        $objectifs = $this->getDoctrine()->getRepository("App:Objectif")->findAll();
        $objectif = $partie->getObjectifs();
        $cartes = $this->getDoctrine()->getRepository(Carte::class)->findAll();
        dump($objectifs);
        dump($objectif);
        return $this->render('partie/plateau.html.twig', ['objectifs' => $objectifs, 'partie' => $partie, 'cartes' => $cartes, 'objectif' => $objectif]);

    }

    /**
     * @Route("/{id}/afficher_actions", name="afficher_actions")
     */
    public function afficherActions(Partie $partie) {
        $joueur = $this->getUser();
        $adversaire = ($joueur == $partie->getJ1()) ? $partie->getJ2() : (($joueur == $partie->getJ2()) ? $partie->getJ1() : 'Erreur');
        $tCartes = ($joueur == $partie->getJ1()) ? $partie->getActionJ2()['cadeau']->cartes : (($joueur == $partie->getJ2()) ? $partie->getActionJ1()['cadeau']->cartes : 'Erreur');
        $tPaire1 = ($joueur == $partie->getJ1()) ? $partie->getActionJ2()['concurrence']->cartes->paire1 : (($joueur == $partie->getJ2()) ? $partie->getActionJ1()['concurrence']->cartes->paire1 : 'Erreur');
        $tPaire2 = ($joueur == $partie->getJ1()) ? $partie->getActionJ2()['concurrence']->cartes->paire2 : (($joueur == $partie->getJ2()) ? $partie->getActionJ1()['concurrence']->cartes->paire2 : 'Erreur');
        $cartesCa = $tCartes;
        $cartesCo = array("paire1" => array(), "paire2" => array());
        $terrain = ($joueur == $partie->getJ1()) ? $partie->getTerrainJ1() : (($joueur == $partie->getJ2()) ? $partie->getTerrainJ2() : 'Erreur');
        $tTerrain = array();

        foreach($tPaire1 as $cartesR1) {
            $cartesCo["paire1"][] = $this->getDoctrine()->getRepository(Carte::class)->find($cartesR1)->getImg();
        }
        foreach($tPaire2 as $cartesR2) {
            $cartesCo["paire2"][] = $this->getDoctrine()->getRepository(Carte::class)->find($cartesR2)->getImg();
        }

        foreach($terrain as $carteTerrain) {
            $tTerrain[] = $this->getDoctrine()->getRepository(Carte::class)->find($carteTerrain)->getImg();
        }

        dump((array) $cartesCa);
        return $this->render('Partie/actions.html.twig', ['partie' => $partie, 'cartesCa' => (array) $cartesCa, 'cartesCo' => $cartesCo, 'terrain' => $tTerrain]);
    }
}