<?php

namespace App\Controller;

use App\Service\CashflowReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CashflowReportController extends AbstractController
{
    #[Route('/finance/cashflow/report', name: 'cashflow_report')]
    public function index(Request $request, CashflowReportService $service): Response
    {
        $company = $this->getUser()->getCompanies()[0];
        $projectId = $request->query->get('project');
        $dateFrom = $request->query->get('dateFrom');
        $dateTo = $request->query->get('dateTo');

        $data = $service->build(
            $company,
            $projectId ?: null,
            $dateFrom ? new \DateTimeImmutable($dateFrom) : null,
            $dateTo ? new \DateTimeImmutable($dateTo) : null,
        );
        return $this->render('finance/cashflow/report_grouped.html.twig', $data);

    }
}
