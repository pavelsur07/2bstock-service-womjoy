<?php

namespace App\Controller;

use App\Entity\CashflowTransaction;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'user' => $this->getUser(),
            'company' => $this->getUser()->getCompanies()[0],
        ]);
    }
}
