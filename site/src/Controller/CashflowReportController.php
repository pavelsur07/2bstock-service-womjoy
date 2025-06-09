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
    public function index(CashflowTransactionRepository $repository, CashflowCategoryRepository $categoryRepository): Response
    {
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

        foreach ($transactions as $txn) {
            $month = $txn->getDate()->format('Y-m');
            $months[$month] = true;

            $category = $txn->getCategory();
            $parent = $category->getParent();
            $parentName = $parent ? $parent->getName() : $category->getName();
            $childName = $parent ? $category->getName() : null;

            $direction = $txn->getDirection();
            $amount = $txn->getAmount();

            if (!isset($report[$parentName])) {
                $report[$parentName] = [];
            }

            if ($childName) {
                $report[$parentName][$childName][$month] = ($report[$parentName][$childName][$month] ?? 0) + $amount;
            }

            $report[$parentName]['__total'][$month] = ($report[$parentName]['__total'][$month] ?? 0) + $amount;

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

        // получаем родительские категории с сортировкой
        $parentCategories = $categoryRepository->findBy([
            'company' => $company,
            'parent' => null
        ], [
            'sortOrder' => 'ASC'
        ]);

        $sortedCategoryNames = [];
        foreach ($parentCategories as $parentCat) {
            $name = $parentCat->getName();
            if (isset($report[$name])) {
                $sortedCategoryNames[] = $name;
            }
        }

        $categoryDirections = [];

        foreach ($transactions as $txn) {
            $category = $txn->getCategory();
            $parent = $category->getParent();
            $parentName = $parent ? $parent->getName() : $category->getName();
            $direction = $txn->getDirection();

            // фиксируем, что эта категория относится к income/expense
            if (!isset($categoryDirections[$parentName])) {
                $categoryDirections[$parentName] = $direction;
            }
        }






        return $this->render('finance/cashflow/report_grouped.html.twig', [
            'report' => $report,
            'monthly' => $monthly,
            'months' => $monthKeys,
            'categories' => $sortedCategoryNames,
            'categoryDirections' => $categoryDirections
        ]);
    }
}
