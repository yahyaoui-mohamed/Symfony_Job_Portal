<?php

namespace App\Controller;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobsController extends AbstractController
{
    #[Route('/jobs', name: 'app_jobs')]
    public function index(EntityManagerInterface $em): Response
    {

        $jobs = $em->getRepository(Job::class)->findAll();
        $numOfJobs = count($jobs);

        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs,
            'jobsCount' => $numOfJobs
        ]);
    }

    #[Route('/jobs/page/{page}', name: 'app_jobs_pages')]
    public function page($page, EntityManagerInterface $em): Response
    {

        $jobs = $em->getRepository(Job::class)->findAll();
        $numOfJobs = count($jobs);

        return $this->render('jobs/jobpage.html.twig', []);
    }
}
