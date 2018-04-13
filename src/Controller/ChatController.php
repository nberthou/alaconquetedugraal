<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 10/04/2018
 * Time: 19:49
 */

namespace App\Controller;


use App\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends Controller
{
    /**
     * @Route("/dire", name="dire")
     */
    public function dire(Request $request) {
        $pseudo = $this->getUser()->getPseudo();
        $message = $request->query->get('message');
        $date = (new \DateTime())->format('d/m/Y H:i:s');

        $chat = new Chat();

        $chat->setPseudo($pseudo);
        $chat->setMessage($message);
        $chat->setDate($date);

        $this->getDoctrine()->getManager()->persist($chat);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse($chat);
    }

   /**
     * @Route("/lire", name="lire")
     */
    public function lire() {

       $messages = $this->getDoctrine()->getRepository(Chat::class)->findBy([], ['date' => 'DESC']);

       foreach($messages as $message) {
           $msg = $message['message'];
           $pseudo = $message['pseudo'];
           echo $pseudo. ' ['.strftime('%H:%M:%S', strtotime($message['date'])) . '] : ' .$msg.'<br><hr><br>';
       }
    }

}