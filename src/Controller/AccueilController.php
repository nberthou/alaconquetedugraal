<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 20/02/2018
 * Time: 08:50
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AccueilController extends Controller
{
    /**
     * @Route("/", name="index")
     * @param Environment $twig
     * @return Response
     */
    public function indexAction() {
        return $this->render('index.html.twig');
    }
}