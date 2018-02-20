<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 20/02/2018
 * Time: 09:06
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parties")
 * Class PartieController
 * @package App\Controller
 */
class PartieController extends Controller
{

    public function index() {

    }

    /**
     * @Route("/nouvelle", name="nouvelle_partie")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function creation() {
        return $this->render('Partie/creation_partie.html.twig');
    }
}