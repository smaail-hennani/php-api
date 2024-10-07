<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//use App\Controller\Security;
// use Symfony\Component\Security\Core\Security;

class UserCompanyController extends AbstractController
{
    //private $security;
    private $companyRepository;

    public function __construct(/*Security $security, */CompanyRepository $companyRepository)
    {
        //$this->security = $security;
        $this->companyRepository = $companyRepository;
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        // Récupérer les sociétés auxquelles l'utilisateur appartient via UserSociety
        $companies = $this->companyRepository->findByUser($user);

        return $this->json($companies);
    }
}
