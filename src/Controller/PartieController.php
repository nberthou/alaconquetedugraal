<?php
/**
 * Created by PhpStorm.
 * User2: Nicolas
 * Date: 20/02/2018
 * Time: 09:06
 */

namespace App\Controller;


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

    public function index() {

    }

    /**
     * @Route("/recherche", name="recherche_joueurs")
     * @return \Symfony\Component\HttpFoundation\Response
     */
   public function rechercheJoueurs() {
       $joueurs = $this->getDoctrine()->getRepository(User::class)->findAll();
       return $this->render('partie/recherche.html.twig', ['joueurs' => $joueurs]);
   }

    /**
     * @Route("/creer", name="creer_partie")
     * @param Request $request
     * @return Response
     */
   public function creerParties(Request $request) {
       $idAdversaire = $request->request->get('adversaire');
       $idJoueur = $this->getUser();
       dump($idJoueur);
       dump($idAdversaire);
       return $this->render('partie/recherche.html.twig');
   }
}