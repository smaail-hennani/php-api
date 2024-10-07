<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Company;
use App\Entity\Project;

class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

   	#[Route('/api/companies/{companyId}/projects', name: 'get_projects', methods: ['GET'])]
    public function getProjects(Company $company): JsonResponse
    {
        $this->denyAccessUnlessGranted('VIEW', $company);
        return $this->json($company->getProjects());
    }

    #[Route('/api/companies/{companyId}/projects', name: 'create_project', methods: ['POST'])]
    public function createProject(Request $request, Company $company): JsonResponse
    {
        $this->denyAccessUnlessGranted('MANAGE', $company);

        $data = json_decode($request->getContent(), true);
        $project = new Project();
        $project->setTitle($data['title']);
        $project->setDescription($data['description']);
        $project->setCreatedAt(new \DateTime());

        // Assigner le projet à la société
        $project->setCompany($company);

        // Sauvegarder en base de données
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($project);
        $entityManager->flush();

        return $this->json($project, 201);
    }
}
