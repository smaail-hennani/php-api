<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    /**
     * @Route("/api/companies", name="get_companies", methods={"GET"})
     */
    public function getCompanies(CompanyRepository $companyRepository): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $companies = $companyRepository->findByUser($user);

        return $this->json($companies);
    }

    /**
     * @Route("/api/companies/{id}", name="get_company", methods={"GET"})
     */
    public function getCompany(Company $company): JsonResponse
    {
        $this->denyAccessUnlessGranted(CompanyVoter::VIEW, $company); // Vérifier le rôle de l'utilisateur
        return $this->json($company);
    }
}
