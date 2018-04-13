<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 02/03/2018
 * Time: 14:33
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/classement")
 * Class ClassementController
 * @package App\Controller
 */
class ClassementController extends Controller
{
    /**
     * @Route("/", name="classement")
     */
    public function index() {
        $joueurs=  $this->getDoctrine()->getRepository(User::class)->orderByParties();
        return $this->render('classement.html.twig', ['joueurs' => $joueurs]);
    }

}