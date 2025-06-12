<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'company_index')]
    public function index(): Response
    {
        $user = $this->getUser();
        $companies = $user ? $user->getCompanies() : [];

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/{id}/edit', name: 'company_edit')]
    public function edit(Company $company): Response
    {
        return $this->render('company/edit.html.twig', [
            'company' => $company,
        ]);
    }
}
