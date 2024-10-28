<?php

namespace App\Controller;

use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobsController extends AbstractController
{
    #[Route('/jobs', name: 'app_jobs')]
    public function index(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {

        $allJobs = $em->getRepository(Job::class)->findAll();
        $query = $em->getRepository(Job::class)->createQueryBuilder('j')->getQuery();
        $jobs = $paginator->paginate(

            $query,
            $request->query->getInt('page', 1),
            5
        );

        $numOfJobs = count($allJobs);

        return $this->render('jobs/index.html.twig', [
            'jobs' => $jobs,
            'jobsCount' => $numOfJobs
        ]);
    }

    #[Route('/jobs/{id}', name: 'app_jobs_pages')]
    public function page($id, EntityManagerInterface $em): Response
    {
        $job = $em->getRepository(Job::class)->find($id);

        return $this->render('jobs/jobpage.html.twig', [
            'job' => $job
        ]);
    }
}
