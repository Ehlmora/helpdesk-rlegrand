<?php

namespace App\Form\User;

use App\Entity\User;
use App\Entity\Role;
use App\Repository\RoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserEditType extends AbstractType
{
    /** 
     * @var RoleRepository
    */
    private $role_repo;

    public function __construct(RoleRepository $role_repo){
        $this->role_repo = $role_repo;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr'  => [
                    'placeholder'   => 'John'
                ]
            ])
            ->add('lastname', TextType::class, [
                'attr'  => [
                    'placeholder'   => 'Doe'
                ]
            ])
            ->add('address', TextType::class, [
                'attr'  => [
                    'placeholder'   => '12 Rue des pétunias'
                ]
            ])
            ->add('city', TextType::class, [
                'attr'  => [
                    'placeholder'   => 'Gotham City'
                ]
            ])
            ->add('postalCode', TextType::class, [
                'attr'  => [
                    'placeholder'   => '76000'
                ]
            ])
            ->add('phone', TextType::class, [
                'attr'  => [
                    'placeholder'   => '06XXXXXXXX'
                ]
            ])
            ->add('mail', TextType::class, [
                'attr'  => [
                    'placeholder'   => 'john.doe@example.com'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required'  => false,
                'attr'  => [
                    'placeholder'   => 'Possède 12 PC et 3 serveurs'
                ]
            ])
            /*->add('username', TextType::class, [
                'empty_data'    => ''
            ])
            ->add('password', PasswordType::class, [
                'empty_data'    => ''
            ])*/
            ->add('role', EntityType::class, [
                'class'     => Role::class,
                'choice_label' => function($role) {
                    return ucfirst($role->getName());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain'=> 'forms',
            'csrf_protection'   => false
        ]);
    }

    private function getAllRoles(){
        $roles = $this->role_repo->findAll();
        $output = [];
        foreach($roles as $r){
            $output[$r->getName()] = $r->getId();
        }
        return $output;
    }
}
