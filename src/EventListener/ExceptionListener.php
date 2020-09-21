<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener
{
    private $router;
    private $security;

    public function __construct(RouterInterface $router, Security $security){
        $this->router = $router;
        $this->security = $security;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        /*$user = $this->security->getUser();
        if(!$event instanceof NotFoundHttpException){
            if($user != null && $user->getRole()->getName() === 'admin'){
                $response = new RedirectResponse($this->router->generate('admin.ticket.index'));
            } else if ($user != null && $user->getRole()->getName() === 'client') {
                $response = new RedirectResponse($this->router->generate('client.ticket.index'));
            } else {
                $response = new RedirectResponse($this->router->generate('login'));
            }
        }
        

        //$response = new RedirectResponse($this->router->generate('login'));
        

        $event->setResponse($response);*/
    }
}