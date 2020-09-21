<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use App\Entity\TicketLog;
use App\Form\Ticket\TicketType;
use App\Form\Ticket\TicketEditType;
use App\Repository\TicketRepository;
use App\Repository\TicketStateRepository;
use App\Repository\TicketLogRepository;
use App\Utils\AppUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Datatable\Type\AdminTicketListType;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\DataTableTypeInterface;



class AdminTicketController extends AbstractController
{
    /** 
     * @var TicketRepository
    */
    private $ticket_repo;

    /**
     * @var TicketStateRepository
     */
    private $ticketState_repo;

    /**
     * @var TicketLogRepository
     */
    private $ticketLog_repo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Security
     */
    private $security;

    public function __construct(TicketRepository $ticket_repo, TicketStateRepository $ticketState_repo, TicketLogRepository $ticketLog_repo, EntityManagerInterface $em, Security $security){
        $this->ticket_repo      = $ticket_repo;
        $this->ticketState_repo = $ticketState_repo;
        $this->ticketLog_repo   = $ticketLog_repo;
        $this->em               = $em;
        $this->security         = $security;
    }

    /**
     * @Route("/admin", name="admin.ticket.index")
     */
    public function index(Request $request, DataTableFactory $dataTableFactory)
    {
        $dataTable = $dataTableFactory->create()
            /*->add('id', TextColumn::class, [
                'label' => '#',
            ])*/
            ->add('firstname', TextColumn::class, [
                'label' => 'Prénom',
                'field' => 'user.firstname',
            ])
            ->add('lastname', TextColumn::class, [
                'label' => 'Nom',
                'field' => 'user.lastname',
            ])
            ->add('title', TextColumn::class, [
                'label' => 'Objet',
            ])
            ->add('ticketState', TextColumn::class, [
                'label'  => 'Etat',
                'field'  => 'ticketState.name',
                'render' => function($value) {
                    if      ($value == "Nouveau")
                        { return '<span class="badge badge-success">'.$value.'</span>'; }
                    else if ($value == "En cours")
                        { return '<span class="badge badge-warning">'.$value.'</span>'; }
                    else
                        { return '<span class="badge badge-secondary">'.$value.'</span>'; }
                },
            ])
            ->add('priority', TextColumn::class, [
                'label' => 'Priorité',
            ])
            ->add('dateStart', DateTimeColumn::class, [
                'label'  => 'Date de début',
                'format' => 'd/m/y'
            ])
            ->add('dateEnd', DateTimeColumn::class, [
                'label'     => 'Date de fin',
                'format'    => 'd/m/y',
                'nullValue' => '/',
            ])
            ->add('description', TextColumn::class, [
                'label' => 'Description'
            ])
            ->add('id', TextColumn::class, [
                'label'  => 'Actions',
                'render' => function($value, $context) {
                    return  sprintf('<div class="row">
                                        <a href="%s">
                                            <i class="fas fa-eye btn"></i>
                                        </a>
                                        <a href="%s">
                                            <i class="fas fa-pen btn"></i>
                                        </a>
                                    </div>', 
                                $this->generateUrl('admin.ticket.show', ['id' => $value]), 
                                $this->generateUrl('admin.ticket.edit', ['id' => $value]));
                }
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Ticket::class,
            ])
            ->handleRequest($request);;

        if ($dataTable->isCallback()) {
            return $dataTable->getResponse();
        }

        return $this->render('admin/ticket/index.html.twig', [
            'current_menu' => 'admin.ticket.index',
            'tickets'      => $this->ticket_repo->findAll(),
            'datatable'    => $dataTable
        ]);
    }

    /**
     * @Route("/admin/tickets/{id}", name="admin.ticket.show")
     */
    public function show(Ticket $ticket){
        return $this->render('admin/ticket/show.html.twig', [
            'current_menu' => 'admin.ticket.index',
            'ticket'       => $ticket,
            'ticket_log'   => $this->ticketLog_repo->findAllByTicketId($ticket->getId())
        ]);
    }

    /**
     * @Route("/admin/new-ticket", name="admin.ticket.new")
     */
    public function new(Request $request){
        $ticket = new Ticket();
        $form   = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $ticket->setTicketState($this->ticketState_repo->find(1));
            $this->em->persist($ticket);
            $this->em->flush();
            return $this->redirectToRoute('admin.ticket.index');
        }

        return $this->render('admin/ticket/new.html.twig', [
            'current_menu' => 'admin.ticket.new',
            'ticket'       => $ticket,
            'form'         => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/tickets/edit/{id}", name="admin.ticket.edit")
     */
    public function edit(Ticket $old_ticket, Request $request){

        $old_array = (array) $old_ticket;

        $form = $this->createForm(TicketEditType::class, $old_ticket);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $new_ticket = $form->getData();
            $new_array  = (array) $new_ticket;
            
            foreach($new_array as $key => $value) {

                if($old_array[$key] !== $new_array[$key]) {
    
                    $log = new TicketLog();
                    $log->setTicket($old_ticket)
                        ->setUpdatedAt(new \DateTime())
                        ->setChangedValue(AppUtils::readableFieldName($key))
                        ->setOldValue(AppUtils::toStringForLog($old_array[$key]))
                        ->setNewValue(AppUtils::toStringForLog($new_array[$key]))
                        ->setUpdatedBy($this->security->getUser())
                    ;
                    $this->em->persist($log);
                    $this->em->flush();
                }
            }
            
            $this->em->persist($new_ticket);
            $this->em->flush();
            return $this->redirectToRoute('admin.ticket.index');
        }
        return $this->render('admin/ticket/edit.html.twig', [
            'current_menu' => 'admin.ticket.index',
            'ticket'       => $old_ticket,
            'form'         => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/tickets/edit/{id}/change-state", name="admin.ticket.changeState")
     */
    /*public function changeState(Ticket $ticket) {

        $old_array = (array) $ticket;

        if($this->ticketState_repo->getMaxId() == $ticket->getTicketState()) {
            $ticket->setTicketState($this->ticketState_repo->find(1));
        } else {
            $this->ticket->setTicketState($ticketState_repo->find($ticket->getTicketState()->getId() + 1));
        }

        $this->ticketLog_repo->createNewLogs($old_array, (array) $ticket);
    }*/
}

