<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecruiterController extends AbstractController
{
    #[Route('/recruiter', name: 'app_recruiter')]
    public function index(): Response
    {
        return $this->render('recruiter/index.html.twig', [
            'controller_name' => 'RecruiterController',
        ]);
    }

    #[Route('/recruiter/parameters', name: 'app_recruiter_parameters')]
    public function parameters(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add("newPassword", PasswordType::class)
            ->add("confirmPassword", PasswordType::class)
            ->add("save", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        }


        return $this->render('recruiter/parameters.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
