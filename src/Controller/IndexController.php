<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
  #[Route("/", name: "app_index")]
  public function index()
  {
    $form = $this->createFormBuilder()
      ->add("job", SearchType::class)
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
      ->add("search", SubmitType::class)
      ->getForm();

    // $form->handleRequest($request);
    // if ($form->isSubmitted() && $form->isValid()) {

    //   $data = $form->getData();
    //   dd($data);
    // }

    // if ($this->getUser()) {
    //   if (in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
    //   }
    // }
    return $this->render("Index/index.html.twig", [
      'form' => $form->createView()
    ]);
  }
}
