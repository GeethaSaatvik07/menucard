<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\RegistrationService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'registration')]
    public function registration(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $passwordHasher, RegistrationService $registartionService): Response
    {
        $registrationForm = $registartionService->createUser($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()){
            $input = $registrationForm->getData();

            $user = new User();
            $user->setUsername($input['username']);

            $user->setPassword(
                $passwordHasher->hashPassword($user, $input['password'])
            );

            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('home'));
        }
        
        return $this->render('registration/index.html.twig', [
            'regform' => $registrationForm->createView()
        ]);
    }
}
