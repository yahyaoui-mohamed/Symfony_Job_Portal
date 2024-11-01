<?php

namespace App\Controller;

use App\Entity\Saved;
use App\Form\SkillType;
use App\Form\EducationType;
use App\Entity\Applications;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AccountController extends AbstractController
{
    #[Route('/user', name: 'app_account')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }

        $user = $this->getUser();

        $form = $this->createFormBuilder()
            ->add("firstname", TextType::class, [
                'attr' => [
                    "class" => 'form-control',
                    'placeholder' => 'First name'
                ]
            ])
            ->add("lastname", TextType::class, [
                'attr' => [
                    "class" => 'form-control',
                    'placeholder' => 'Last name'
                ],
            ])
            ->add("email", EmailType::class, [
                'attr' => [
                    "class" => 'form-control',
                    'placeholder' => 'Email'
                ]
            ])
            ->add("phone", TextType::class, [
                'attr' => [
                    "class" => 'form-control',
                    'placeholder' => 'Phone',
                ],
                'required' => false
            ])
            ->add("cv", FileType::class, [
                'attr' => [
                    "class" => 'form-control',
                    'placeholder' => 'Phone',
                ],
                'required' => false
            ],)
            ->add("education", CollectionType::class, [
                'entry_type' => EducationType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
                'data' => $user->getEducation(),
            ])
            ->add("experience", CollectionType::class, [
                'entry_type' => ExperienceType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
                'data' => $user->getExperiences(),
            ])
            ->add("skill", CollectionType::class, [
                'entry_type' => SkillType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
                'data' => $user->getSkills(),
            ])
            ->add("save", SubmitType::class, [
                'attr' => [
                    "class" => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('education')->getData() as $education) {
                $education->setUser($user);
                $em->persist($education);
            }
            foreach ($form->get('experience')->getData() as $experience) {
                $experience->setUser($user);
                $em->persist($experience);
            }

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_account");
        }
        return $this->render('User/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/user/applications', name: 'app_account_applications')]
    public function applications(EntityManagerInterface $em): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }

        $repository = $em->getRepository(Applications::class);
        $apps = $repository->findBy(['user' => $this->getUser()->getId()]);

        return $this->render('User/applications.html.twig', [
            'apps' => $apps
        ]);
    }

    #[Route('/user/saved', name: 'app_account_saved')]
    public function saved(EntityManagerInterface $em): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }

        $repository = $em->getRepository(Saved::class);
        $apps = $repository->findBy(['user' => $this->getUser()->getId()]);
        return $this->render('User/saved.html.twig', [
            'apps' => $apps
        ]);
    }

    #[Route('/user/parameters', name: 'app_account_parameters')]
    public function parameters(Request $request, EntityManagerInterface $em): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }
        $form = $this->createFormBuilder()
            ->add("newPassword", PasswordType::class)
            ->add("confirmPassword", PasswordType::class)
            ->add("save", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
        }
        return $this->render('User/parameters.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
