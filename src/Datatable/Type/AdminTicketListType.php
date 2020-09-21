<?php 

namespace App\Datatable\Type;

use App\Entity\Ticket;

use Symfony\Component\HttpFoundation\Request;

use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\DataTableTypeInterface;

class AdminTicketListType implements DataTableTypeInterface
{

    /**
     * @var DataTableFactory
     */
    private $dataTableFactory;

    public function __construct(DataTableFactory $dataTableFactory) {
        $this->dataTableFactory = $dataTableFactory;
    }

    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable = $this->dataTableFactory->create()
            ->add('id', TextColumn::class, [
                'label' => '#',
            ])
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
                'render' => function($ticketState) {
                    if      ($ticketState == "Nouveau")
                        { return '<span class="badge badge-success">'.$ticketState.'</span>'; }
                    else if ($ticketState == "En cours")
                        { return '<span class="badge badge-warning">'.$ticketState.'</span>'; }
                    else
                        { return '<span class="badge badge-secondary">'.$ticketState.'</span>'; }
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
            ->createAdapter(ORMAdapter::class, [
                'entity' => Ticket::class,
            ]);

        if ($dataTable->isCallback()) {
            return $dataTable->getResponse();
        }

        return $dataTable;
    }
}