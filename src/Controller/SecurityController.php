<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute(
                $this->security
                    ->getUser()
                    ->getRole()
                    ->getName() . '.ticket.index');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/redirect", name="login.redirect")
     */
    public function loginRedirection(){
        
    }

    public function createPasswordAfterRegister() {
        
    }
}
