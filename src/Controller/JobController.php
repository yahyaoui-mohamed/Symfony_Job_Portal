<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobController extends AbstractController
{
    #[Route('/job', name: 'app_job')]
    public function index(EntityManagerInterface $em): Response
    {
        $jobs = $em->getRepository(Job::class)->findAll();
        return $this->render('job/index.html.twig', [
            'jobs' => $jobs
        ]);
    }

    #[Route('/job/add', name: 'app_add_job')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute("app_add_job");
        }
        return $this->render('job/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/job/edit/{id}', name: 'app_edit_job')]
    public function edit(Job $job, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(JobType::class, $job);
        $id = $job->getId();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();
            // Add flash message
            return $this->redirectToRoute("app_edit_job", ['id' => $id]);
        }
        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
            'job' => $job
        ]);
    }


    #[Route('/job/delete/{id}', name: 'app_delete_job')]
    public function delete(Job $job, Request $request, EntityManagerInterface $em): Response
    {
        $em->remove($job);
        $em->flush();
        return $this->redirectToRoute("app_job");
    }
}