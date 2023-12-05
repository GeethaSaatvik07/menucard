<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationService{

    private $doctrine;
    private $formFactory;
    private $passwordHasher;

    public function __construct(ManagerRegistry $doctrine, FormFactoryInterface $formFactory, UserPasswordHasherInterface $passwordHasher){
        $this->doctrine = $doctrine;
        $this->formFactory = $formFactory;
        $this->passwordHasher = $passwordHasher;
    }   

    public function createUser(Request $request)
    {
        $registrationForm = $this->formFactory->createBuilder()
        ->add('username', TextType::class,[
            'label' => "Employee"])
        ->add('password', RepeatedType::class,[
            'type' => PasswordType::class,
            'constraints' => [
                    new Length(['min' => '8']),
                    new Regex([
                        'pattern' => '/[!@#$%^&*(),.?":{}|<>]/',
                        'message' => 'The password must contain atleast one special character.'
                    ])
                ],
            'required' => true,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => "Repeat Password"]
        ])
        ->add("register", SubmitType::class)
        ->getForm();

        $registrationForm->handleRequest($request);
        
        return $registrationForm;
    }
}