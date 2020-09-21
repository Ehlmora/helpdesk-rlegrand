<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\User\UserType;
use App\Form\User\UserEditType;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Repository\TicketRepository;
use App\Notification\MailNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Controller\ResetPasswordController;
use Symfony\Component\Mailer\MailerInterface;

class AdminUserController extends AbstractController
{
    /** 
     * @var UserRepository
    */
    private $user_repo;

    /**
     * @var TicketRepository
     */
    private $ticket_repo;

    /**
     * @var RoleRepository
     */
    private $role_repo;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $user_repo, TicketRepository $ticket_repo, RoleRepository $role_repo, EntityManagerInterface $em){
        $this->user_repo   = $user_repo;
        $this->ticket_repo = $ticket_repo;
        $this->role_repo   = $role_repo;
        $this->em          = $em;
    }
    /**
     * @Route("/admin/users", name="admin.user.index")
     */
    public function index()
    {
        $users = $this->user_repo->findAll();
        dump($users);
        return $this->render('admin/user/index.html.twig', [
            'current_menu'    => 'admin.user.index',
            'users'           => $users
        ]);
    }

    /**
     * @Route("/admin/users/{id}", name="admin.user.show")
     */
    public function show(User $user){
        return $this->render('admin/user/show.html.twig', [
            'current_menu'    => 'admin.user.index',
            'user'            => $user,
            'tickets'         => $this->ticket_repo->findAllByUserId($user->getId())
        ]);
    }

    /**
     * @Route("/admin/new-user", name="admin.user.new")
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailNotification $notification, ResetPasswordController $reset_password, MailerInterface $mailer){
        $user = new User();
        $submittedToken = $request->request->get('token');

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {            
            do {
                $username  = strtolower(substr(preg_replace('#[^a-z]#i', '', $user->getLastname()), 0, 2));
                $username .= strtolower(substr(preg_replace('#[^a-z]#i', '', $user->getFirstname()), 0, 2));
                $username .= rand(1000, 9999);
            } while($this->user_repo->verifyUsername($username));

            $user->setUsername($username);
            $user->setPassword($passwordEncoder->encodePassword($user, random_bytes(5)));
            $user->setRole($this->role_repo->find(2));

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Ajouté avec succès');

            //$notification->signup($user);
            $reset_password->resetPasswordAfterRegistration($user, $mailer);

            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/new.html.twig', [
            'current_menu'    => 'admin.user.new',
            'form'            => $form->createView()
        ]);
    }

    //! Ne traite pas le formulaire
    /**
     * @Route("/admin/edit/{id}", name="admin.user.edit")
     */
    public function edit(User $user, Request $request){
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user->setUsername($user->getUsername())
                ->setPassword($user->getPassword())
                ->setRole($user->getRole());

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Modifié avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'current_menu'    => 'admin.user.index',
            'user'            => $user,
            'form'            => $form->createView()
        ]);
    }
}
