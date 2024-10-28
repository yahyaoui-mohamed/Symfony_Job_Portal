<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class SignupController extends AbstractController
{
    #[Route('/signup', name: 'app_signup')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute("app_index");
        }
        $form = $this->createFormBuilder()
            ->add("role", ChoiceType::class, [
                'choices' => [
                    'Recruteur' => 'ROLE_RECRUITER',
                    'Candidat' => 'ROLE_USER'
                ],
                'expanded' => true
            ])
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("email", EmailType::class)
            ->add("motdepasse", PasswordType::class)
            ->add("Sign_up", SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $user = new User();

            $hashedPassword = $passwordHasher->hashPassword($user, $data["motdepasse"]);

            $user->setRoles([$data["role"]]);
            $user->setNom($data["nom"]);
            $user->setPrenom($data["prenom"]);
            $user->setEmail($data["email"]);
            $user->setRegisteredAt(new DateTimeImmutable());
            $user->setPassword($hashedPassword);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Your account has been successfully created!');
            return $this->redirectToRoute('app_signup');
        }
        return $this->render('signup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
