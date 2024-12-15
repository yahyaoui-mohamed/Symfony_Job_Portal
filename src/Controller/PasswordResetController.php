<?php

// src/Controller/PasswordResetController.php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\PasswordReset;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PasswordResetController extends AbstractController
{
    #[Route('/password/reset', name: 'app_password_reset')]
    public function index(Request $request, EmailService $emailService, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add("email", EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email'
                ]
            ])
            ->add("submit", SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];


            // $email = (new Email())
            //     ->from('hamayah4@gmail.com')
            //     ->to($email)
            //     ->subject('Time for Symfony Mailer!')
            //     ->text('Sending emails is fun again!')
            //     ->html('<p>See Twig integration for better HTML integration!</p>');

            // $mailer->send($email);

            $token = bin2hex(random_bytes(32));

            $resetLink = "http://localhost:8000" . $this->generateUrl('app_password_reset_confirm', [
                'token' => $token
            ], true);

            $user = $em->getRepository(User::class)->findOneBy(["email" => $email]);
            $emailService->sendPasswordResetEmail($email, $resetLink);

            $passwordReset = new PasswordReset();
            $passwordReset->setUser($user);
            $passwordReset->setToken($token);
            $passwordReset->setCreatedAt(new DateTimeImmutable());
            $passwordReset->setExpiresAt(new DateTimeImmutable('+1 hour'));
            $em->persist($passwordReset);
            $em->flush();

            $this->addFlash('success', 'A password reset link has been sent to your email.');

            return $this->redirectToRoute('app_password_reset');
        }

        return $this->render('Index/password_reset/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
