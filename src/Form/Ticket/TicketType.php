<?php

namespace App\Form\Ticket;

use App\Entity\Ticket;
use App\Entity\TicketState;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\TicketStateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TicketType extends AbstractType
{
    /** 
     * @var UserRepository
    */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TicketStateRepository
     */
    private $ticketState_repo;

    /**
     * @var Security
     */
    private $security;

    public function __construct(UserRepository $repository, TicketStateRepository $ticketState_repo, EntityManagerInterface $em, Security $security){
        $this->repository             = $repository;
        $this->ticketState_repository = $ticketState_repo;
        $this->em                     = $em;
        $this->security               = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3
                ],
                'empty_data' => '1'
            ])
            ->add('dateStart', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            /*->add('dateEnd', DateTimeType::class, [
                'widget'     => 'single_text',
                'required'   => false,
                'empty_data' => null
            ])*/
            ->add('description', TextareaType::class)
            ->add('user', EntityType::class, [
                'class'        => User::class,
                'choice_label' => function($user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
                'data'    => $this->security->getUser(),
                'group_by'=> function($user) {
                    return ucfirst($user->getRole()->getName());
                }
            ])
            /*->add('ticketState', EntityType::class, [
                'class' => TicketState::class,
                'data'  => $this->ticketState_repo->find(1)
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Ticket::class,
            'translation_domain' => 'forms'
        ]);
    }
}
