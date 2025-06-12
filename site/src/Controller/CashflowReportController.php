<?php

namespace App\Controller;

use App\Repository\CashflowCategoryRepository;
use App\Repository\CashflowTransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CashflowReportController extends AbstractController
{
    #[Route('/finance/cashflow/report', name: 'cashflow_report')]
    public function index(
        CashflowTransactionRepository $repository,
        CashflowCategoryRepository $categoryRepository
    ): Response {
        $company = $this->getUser()->getCompanies()[0];
        $transactions = $repository->listByCompanyId($company->getId());

        $months = [];
        $report = [];
        $balances = [];

        // стартовый баланс по всем счетам
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

        // список месяцев как отсортированный массив
        $monthKeys = array_keys($months);

        // расчёт остатков
        $monthly = [];
        $runningBalance = $startBalance;
        foreach ($monthKeys as $month) {
            $income = $balances[$month]['income'] ?? 0;
            $expense = $balances[$month]['expense'] ?? 0;
            $monthly[$month]['start'] = $runningBalance;
            $runningBalance += $income - $expense;
            $monthly[$month]['end'] = $runningBalance;
        }

        // получаем корневые категории с сортировкой
        $rootCategories = $categoryRepository->findBy([
            'company' => $company,
            'parent' => null,
        ], [
            'sortOrder' => 'ASC',
        ]);






        return $this->render('finance/cashflow/report_grouped.html.twig', [
            'report' => $report,
            'monthly' => $monthly,
            'months' => $monthKeys,
            'rootCategories' => $rootCategories,
        ]);
    }
}
