<?php

namespace App\Controller;

use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class JobSearchController extends AbstractController
{
    #[Route('/search', name: 'app_job_search')]
    public function index(Request $request, SessionInterface $session, JobRepository $jobRepository): Response
    {
        $searchData = $session->get('job_search_data', []);
        $job = $searchData["job"];
        $location = $searchData["location"];

        $jobs = $jobRepository->findJobsByCriteria($job, $location);

        return $this->render('Index/job_search/index.html.twig', [
            'jobsCount' => count($jobs),
            'jobs' => $jobs,
        ]);
    }
}
