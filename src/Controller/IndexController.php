<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
  #[Route("/", name: "app_index")]
  public function index(Request $request, SessionInterface $session)
  {
    $form = $this->createFormBuilder()
      ->add("job", SearchType::class, [
        'attr' => [
          'class' => 'search-bar form-control',
          'placeholder' => 'Job title or keywords'
        ]
      ])
      ->add("location", ChoiceType::class, [
        'choices' => [
          'France' => 'France',
          'Germany' => 'Germany',
          'Morocco' => 'Morocco',
          'Spain' => 'Spain',
          'England' => 'England',
          'Tunisia' => 'Tunisia',
          'USA' => 'USA',
        ],
        'placeholder' => 'Choose a location',
        'attr' => [
          'class' => 'form-select'
        ]
      ])
      ->add("search", SubmitType::class, [
        'attr' => [
          'class' => 'btn btn-search',
          'value' => 'Search'
        ]
      ])
      ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $data = $form->getData();

      $session->set('job_search_data', [
        'job' => $data["job"],
        'location' => $data["location"],
      ]);

      return $this->redirectToRoute("app_job_search");
    }

    return $this->render("Index/index.html.twig", [
      'form' => $form->createView()
    ]);
  }
}
