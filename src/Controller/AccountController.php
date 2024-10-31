<?php

namespace App\Controller;

use App\Entity\Applications;
use App\Form\EducationType;
use App\Form\ExperienceType;
use App\Repository\ApplicationsRepository;
use App\Form\SkillType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountController extends AbstractController
{
    #[Route('/user', name: 'app_account')]
    public function index(): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }
        return $this->render('account/index.html.twig', []);
    }

    #[Route('/user/applications', name: 'app_account_applications')]
    public function applications(EntityManagerInterface $em): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }

        $repository = $em->getRepository(Applications::class);
        $apps = $repository->findBy(['user' => $this->getUser()->getId()]);

        return $this->render('account/applications.html.twig', [
            'apps' => $apps
        ]);
    }

    #[Route('/user/saved', name: 'app_account_saved')]
    public function saved(EntityManagerInterface $em): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }

        $repository = $em->getRepository(Applications::class);
        $apps = $repository->findBy(['user' => $this->getUser()->getId()]);
        return $this->render('account/saved.html.twig', [
            'apps' => $apps
        ]);
    }


    #[Route('/user/profile', name: 'app_user_profile')]
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
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
                'entry_type' => EducationType::class, // Add your EducationType form here
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Education',
                'prototype' => true,
                'prototype_name' => '__name__',
                'data' => $user->getEducation(), // Pass existing education data
            ])
            ->add("experience", CollectionType::class, [
                'entry_type' => ExperienceType::class, // Add your EducationType form here
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Education',
                'prototype' => true,
                'prototype_name' => '__name__',
                'data' => $user->getExperiences(), // Pass existing education data
            ])
            ->add("skill", CollectionType::class, [
                'entry_type' => SkillType::class, // Add your EducationType form here
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Education',
                'prototype' => true,
                'prototype_name' => '__name__',
                'data' => $user->getSkills(), // Pass existing education data
            ])
            ->add("save", SubmitType::class, [
                'attr' => [
                    "class" => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            foreach ($form->get('education')->getData() as $education) {
                $education->setUser($user); // Associate each education entry with the user
                $em->persist($education);   // Persist each education entry, whether it's new or updated
            }
            foreach ($form->get('experience')->getData() as $experience) {
                $experience->setUser($user); // Associate each education entry with the user
                $em->persist($experience);   // Persist each education entry, whether it's new or updated
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_user_profile");
        }
        return $this->render('user_profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
