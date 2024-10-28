<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
  #[Route("/", name: "app_index")]
  public function index(Request $request)
  {
    $form = $this->createFormBuilder()
      ->add("job", SearchType::class)
      ->add("chercher", SubmitType::class)
      ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {

      $data = $form->getData();
      dd($data);
    }

    if ($this->getUser()) {
      if (in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
      }
    }
    return $this->render(
      "index.html.twig",
      [
        'search' => $form->createView()
      ]
    );
  }
}
