<?php

namespace App\Service;

use App\Entity\Company;
use App\Repository\CashflowCategoryRepository;
use App\Repository\CashflowTransactionRepository;
use App\Repository\ProjectRepository;

class CashflowReportService
{
    private CashflowTransactionRepository $transactionRepository;
    private CashflowCategoryRepository $categoryRepository;
    private ProjectRepository $projectRepository;

    public function __construct(
        CashflowTransactionRepository $transactionRepository,
        CashflowCategoryRepository $categoryRepository,
        ProjectRepository $projectRepository,
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->categoryRepository = $categoryRepository;
        $this->projectRepository = $projectRepository;
    }

    public function build(Company $company, ?string $projectId = null): array
    {
        $transactions = $this->transactionRepository->listByCompanyId($company->getId(), $projectId);

        $months = [];
        $report = [];
        $balances = [];

        $startBalance = 0;
        foreach ($company->getCashAccounts() as $account) {
            $startBalance += $account->getOpeningBalance();
        }

        $addToReport = function (&$node, array $path, string $month, float $amount) use (&$addToReport) {
            $name = array_shift($path);
            if (!isset($node[$name])) {
                $node[$name] = ['__total' => [], '__children' => []];
            }
            $node[$name]['__total'][$month] = ($node[$name]['__total'][$month] ?? 0) + $amount;
            if ($path) {
                $addToReport($node[$name]['__children'], $path, $month, $amount);
            }
        };

        foreach ($transactions as $txn) {
            $month = $txn->getDate()->format('Y-m');
            $months[$month] = true;

            $category = $txn->getCategory();
            $path = [];
            $cur = $category;
            while ($cur !== null && count($path) < 4) {
                array_unshift($path, $cur->getName());
                $cur = $cur->getParent();
            }

            $amount = $txn->getAmount();
            $direction = $txn->getDirection();
            $signed = $direction === 'expense' ? -$amount : $amount;

            $addToReport($report, $path, $month, $signed);

            $balances[$month][$direction] = ($balances[$month][$direction] ?? 0) + $amount;
        }

        ksort($months);
        $monthKeys = array_keys($months);

        $monthly = [];
        $runningBalance = $startBalance;
        foreach ($monthKeys as $month) {
            $income = $balances[$month]['income'] ?? 0;
            $expense = $balances[$month]['expense'] ?? 0;
            $monthly[$month]['start'] = $runningBalance;
            $runningBalance += $income - $expense;
            $monthly[$month]['end'] = $runningBalance;
        }

        $rootCategories = $this->categoryRepository->findBy([
            'company' => $company,
            'parent' => null,
        ], [
            'sortOrder' => 'ASC',
        ]);

        $projects = $this->projectRepository->listByCompanyId($company->getId());

        return [
            'report' => $report,
            'monthly' => $monthly,
            'months' => $monthKeys,
            'rootCategories' => $rootCategories,
            'projects' => $projects,
            'selectedProject' => $projectId,
        ];
    }
}
