<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'project_index')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'project_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $project = new Project(Uuid::uuid4()->toString());
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'project_edit')]
    public function edit(Project $project, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('project_index');
        }

        return $this->render('project/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    #[Route('/{id}/delete', name: 'project_delete')]
    public function delete(Project $project, EntityManagerInterface $em): Response
    {
        $em->remove($project);
        $em->flush();

        return $this->redirectToRoute('project_index');
    }
}
