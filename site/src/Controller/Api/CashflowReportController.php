<?php

namespace App\Controller\Api;

use App\Service\CashflowReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CashflowReportController extends AbstractController
{
    #[Route('/api/finance/cashflow/report', name: 'api_cashflow_report')]
    public function index(Request $request, CashflowReportService $service): JsonResponse
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

        // convert entities to simple arrays for JSON response
        $data['rootCategories'] = array_map(static fn($c) => [
            'id' => $c->getId(),
            'name' => $c->getName(),
        ], $data['rootCategories']);
        $data['projects'] = array_map(static fn($p) => [
            'id' => $p->getId(),
            'name' => $p->getName(),
        ], $data['projects']);

        return new JsonResponse($data);
    }
}
