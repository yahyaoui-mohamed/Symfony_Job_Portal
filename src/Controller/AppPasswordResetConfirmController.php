<?php

namespace App\Controller;

use App\Entity\PasswordReset;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AppPasswordResetConfirmController extends AbstractController
{
    #[Route('/password/confirm/{token}', name: 'app_password_reset_confirm')]
    public function index($token, EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $token = $em->getRepository(PasswordReset::class)->findOneBy(['token' => $token]);
        if ($token) {
            $form = $this->createFormBuilder()
                ->add("password", PasswordType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add("confirmPassword", PasswordType::class, [
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add("save", SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-primary'
                    ]
                ])
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $user = $em->getRepository(User::class)->findOneBy(['id' => $token->getUser()]);
                $password = $passwordHasher->hashPassword($user, $data["password"]);
                $user->setPassword($password);
                $em->flush();
                return $this->redirectToRoute("app_login");
            }
            return $this->render('app_password_reset_confirm/index.html.twig', [
                'form' => $form,
            ]);
        } else {
            return $this->redirectToRoute("app_login");
        }
    }
}
