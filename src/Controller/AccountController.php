<?php

namespace App\Controller;

use App\Entity\Saved;
use App\Form\EducationType;
use App\Entity\Applications;
use App\Entity\Skill;
use App\Form\ExperienceType;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use App\Service\SkillsGenerator;
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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function PHPSTORM_META\type;

class AccountController extends AbstractController
{
    #[Route('/user', name: 'app_account')]
    public function index(Request $request, EntityManagerInterface $em, SkillsGenerator $skillsGenerator): Response
    {
        $user = $this->getUser();

        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }

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
                'label' => false
            ])
            ->add("experience", CollectionType::class, [
                'entry_type' => ExperienceType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => '__name__',
                'data' => $user->getExperiences(),
                'label' => false
            ])
            ->add("skill", HiddenType::class, [
                'label' => false,
            ])
            ->add("save", SubmitType::class, [
                'attr' => [
                    "class" => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);
        $userSkills = [];
        foreach ($user->getSkills()->toArray() as $skill) {
            $userSkills[] = $skill->getSkill();
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($form->get('education')->getData() as $education) {
                $education->setUser($user);
                $em->persist($education);
            }
            foreach ($form->get('experience')->getData() as $experience) {
                $experience->setUser($user);
                $em->persist($experience);
            }

            $skills = explode(",", $data["skill"]);

            foreach ($skills as $skill) {
                if (!empty($skill)) {
                    $newSkill = new Skill();
                    $newSkill->setSkill($skill);
                    $newSkill->setUser($user);
                    $em->persist($newSkill);
                }
            }

            $cv = $form->get('cv')->getData();
            if ($cv) {
                $originalName = $cv->getClientOriginalName();
                $cvFilename = uniqid() . '.' . $cv->guessExtension();
                $cv->move(
                    $this->getParameter('cv_directory'),
                    $cvFilename
                );

                $user->setCV($cvFilename);
                $user->setcvName($originalName);
            }
            $user->setNom($data['lastname']);
            $user->setPrenom($data['firstname']);
            $user->setEmail($data['email']);
            $user->setTelephone($data['phone']);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_account");
        }

        return $this->render('User/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'skills' => $skillsGenerator->getSkills(),
            'userSkills' => $userSkills,
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
    public function parameters(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!in_array("ROLE_USER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute(("app_index"));
        }
        $user = $this->getUser();
        $form = $this->createFormBuilder()
            ->add("avatar", FileType::class, [
                'required' => false,
                'attr' => [
                    'id' => 'fileInput'
                ],
                'label' => false
            ])
            ->add("newPassword", PasswordType::class, [
                'required' => false
            ])
            ->add("confirmPassword", PasswordType::class, [
                'required' => false
            ])
            ->add("save", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data["avatar"]) {
                $avatar = $form->get('avatar')->getData();
                $avatarName = uniqid() . '.' . $avatar->guessExtension();
                $avatar->move(
                    $this->getParameter('avatar_directory'),
                    $avatarName
                );
                $user->setUserAvatar($avatarName);
            }
            if ($data["newPassword"]) {
                $password = $passwordHasher->hashPassword($user, $data["newPassword"]);
                $user->setPassword($data["avatar"]);
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("app_account_parameters");
        }
        return $this->render('User/parameters.html.twig', [
            'form' => $form->createView(),
            'user' => $this->getUser(),
        ]);
    }
}
