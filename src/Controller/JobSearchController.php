<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobSearchController extends AbstractController
{
    #[Route('/search', name: 'app_job_search')]
    public function index(): Response
    {
        return $this->render('job_search/index.html.twig', [
            'controller_name' => 'JobSearchController',
        ]);
    }
}
