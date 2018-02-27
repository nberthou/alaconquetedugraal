<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 22/02/2018
 * Time: 10:26
 */

namespace App\Security;


use App\Entity\User;
use App\Form\ConnexionType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    private $formFactory;
    private $em;
    private $router;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $em, RouterInterface $router)
    {

        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('connexion');
    }

    public function supports(Request $request)
    {
        // TODO: Implement supports() method.
    }

    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/connexion' && $request->isMethod('POST');
        if(!$isLoginSubmit) {
            return;
        }

        $form = $this->formFactory->create(ConnexionType::class);
        $form->handleRequest($request);

        $data = $form->getData();
        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $pseudo = $credentials['pseudo'];
        return $this->em->getRepository(User::class)
            ->findOneBy(['pseudo' => $pseudo]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $mdp = $credentials['mdp'];
        if($mdp == 'azgold')
        {
            return true;
        }

        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->router->generate('/profil');
    }


}