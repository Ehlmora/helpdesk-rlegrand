<?php

namespace App\Controller\Client;

use App\Entity\Ticket;
use App\Form\Ticket\TicketType;
use App\Form\Ticket\TicketEditType;
use App\Repository\TicketRepository;
use App\Repository\TicketStateRepository;
use App\Repository\TicketLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ClientTicketController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    /** 
     * @var TicketRepository
    */
    private $repository;

    /**
     * @var TicketStateRepository
     */
    private $ticketState_repo;

    /**
     * @var TicketLogRepository;
     */
    private $ticketLog_repo;

    /**
     * @var ObjectManager
     */
    private $em;

    public function __construct(Security $security, TicketRepository $repository, TicketStateRepository $ticketState_repo, TicketLogRepository $ticketLog_repo, EntityManagerInterface $em){
        $this->security         = $security;
        $this->repository       = $repository;
        $this->ticketState_repo = $ticketState_repo;
        $this->ticketLog_repo   = $ticketLog_repo;
        $this->em               = $em;
    }
    /**
     * @Route("/client", name="client.ticket.index")
     */
    public function index()
    {
        $tickets = $this->repository->findAllByUserId($this->security->getUser()->getId());
        return $this->render('client/ticket/index.html.twig', [
            'current_menu' => 'ticket.index',
            'tickets'      => $tickets
        ]);
    }

    /**
     * @Route("/client/new-ticket", name="client.ticket.new")
     */
    public function new(Request $request){
        $ticket = new Ticket();
        $form   = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $ticket->setUser($this->security->getUser());
            $ticket->setTicketState($this->ticketState_repo->find(1));
            $this->em->persist($ticket);
            $this->em->flush();
            return $this->redirectToRoute('client.ticket.index');
        }

        return $this->render('client/ticket/new.html.twig', [
            'current_menu' => 'client.ticket.new',
            'ticket'       => $ticket,
            'form'         => $form->createView()
        ]);
    }

    /**
     * @Route("/client/tickets/{id}", name="client.ticket.show")
     */
    public function show($id){
        $ticket = $this->repository->find($id);
        dump($ticket);
        return $this->render('client/ticket/show.html.twig', [
            'current_menu' => 'client.ticket.index',
            'ticket'       => $ticket,
            'ticket_log'   => $this->ticketLog_repo->findAllByTicketId($ticket->getId())
        ]);
    }
}
