<?php

namespace App\Form\Ticket;

use App\Entity\Ticket;
use App\Entity\TicketState;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class TicketEditType extends AbstractType
{
    /** 
     * @var UserRepository
    */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $repository, EntityManagerInterface $em){
        $this->repository = $repository;
        $this->em         = $em;
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
                ]
            ])
            ->add('dateStart', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('dateEnd', DateTimeType::class, [
                'widget'   => 'single_text',
                'required' => false
            ])
            ->add('description', TextareaType::class)
            ->add('user', EntityType::class, [
                'class'        => User::class,
                'choice_label' => function($user) {
                    return $user->getFirstname() . ' ' . $user->getLastname() . ' - ' . $user->getUsername() ;
                },
                'group_by'=> function($user) {
                    return ucfirst($user->getRole()->getName());
                }
            ])
            ->add('ticketState', EntityType::class, [
                'class'        => TicketState::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Ticket::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getAllUsers(){
        $users  = $this->repository->findAllByRole('client');
        $output = [];
        foreach($users as $u){
            $output[$u->getFirstname().' '.$u->getLastname()] = $u->getId();
        }
        return $output;
    }
}
