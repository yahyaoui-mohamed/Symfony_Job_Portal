<?php

namespace App\Controller;

use App\Entity\Applications;
use App\Entity\Job;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobApplyController extends AbstractController
{
    #[Route('/job/apply/{id}', name: 'app_job_apply')]
    public function index($id, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("email", EmailType::class)
            ->add("telephone", TextType::class)
            ->add("cv", FileType::class, [
                'label' => 'Your CV'
            ])
            ->add("coverletter", FileType::class, [
                'label' => 'Cover Letter',
                'required' => false,
            ])
            ->add("apply", SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cv = $form->get('cv')->getData();
            $cover = $form->get('coverletter')->getData();

            $job = $em->getRepository(Job::class)->find($id);

            $app = new Applications();
            $app->setUser($this->getUser());
            $app->setJob($job);
            $app->setAppliedAt(new DateTimeImmutable());

            $cvFilename = uniqid() . '.' . $cv->guessExtension();
            $cv->move(
                $this->getParameter('cv_directory'),
                $cvFilename
            );

            $app->setCv($cvFilename);

            if ($cover) {
                $coverFilename = uniqid() . '.' . $cover->guessExtension();
                $cover->move(
                    $this->getParameter('coverletter_directory'),
                    $coverFilename
                );
                $app->setCoverLetter($coverFilename);
            }
            $em->persist($app);
            $em->flush();
            return $this->redirectToRoute("app_job_apply", ['id' => $id]);
        }
        return $this->render('job_apply/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
