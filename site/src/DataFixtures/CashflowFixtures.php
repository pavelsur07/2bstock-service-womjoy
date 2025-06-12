<?php

namespace App\DataFixtures;


use App\Entity\CashAccount;
use App\Entity\CashflowCategory;
use App\Entity\CashflowTransaction;
use App\Entity\Company;
use App\Entity\Project;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CashflowFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $company = $this->getReference(CompanyFixtures::REFERENCE_COMPANY, Company::class);
        $project = $this->getReference(ProjectFixtures::REFERENCE_PROJECT, Project::class);

        // --- Счета --- //
        $bank = new CashAccount(Uuid::uuid4()->toString());
        $bank->setCompany($company);
        $bank->setName('Банковский счет основной');
        $bank->setType(CashAccount::BANK);
        $bank->setOpeningBalance(200000);
        $manager->persist($bank);

        $cash = new CashAccount(Uuid::uuid4()->toString());
        $cash->setCompany($company);
        $cash->setName('Касса основная');
        $cash->setType(CashAccount::CASH);
        $cash->setOpeningBalance(50000);
        $manager->persist($cash);

        // --- Категории --- //
        $op = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $op->setName('Операционная деятельность')->setCompany($company)->setSortOrder(1);
        $manager->persist($op);

        $revenue = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $revenue->setName('Выручка')->setParent($op)->setCompany($company)->setSortOrder(10);
        $manager->persist($revenue);

        $revenueOzon = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $revenueOzon->setName('Озон')->setParent($revenue)->setCompany($company)->setSortOrder(20);
        $manager->persist($revenueOzon);

        $revenueWb = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $revenueWb->setName('ВБ')->setParent($revenue)->setCompany($company)->setSortOrder(30);
        $manager->persist($revenueWb);

        $materials = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $materials->setName('Материалы')->setParent($op)->setCompany($company)->setSortOrder(40);
        $manager->persist($materials);

        $contractors = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $contractors->setName('Подрядчики')->setParent($op)->setCompany($company)->setSortOrder(50);
        $manager->persist($contractors);

        $fin = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $fin->setName('Финансовая деятельность')->setCompany($company)->setSortOrder(2);
        $manager->persist($fin);

        $loansIncome = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $loansIncome->setName('Получение кредитов')->setParent($fin)->setCompany($company)->setSortOrder(60);
        $manager->persist($loansIncome);

        $loansPayment = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $loansPayment->setName('Выплата кредитов')->setParent($fin)->setCompany($company)->setSortOrder(70);
        $manager->persist($loansPayment);

        $inv = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $inv->setName('Инвестиционная деятельность')->setCompany($company)->setSortOrder(3);
        $manager->persist($inv);

        $investX = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $investX->setName('Инвестиции в проект Х')->setParent($inv)->setCompany($company)->setSortOrder(80);
        $manager->persist($investX);

        $dividendsX = new CashflowCategory(Uuid::uuid4()->toString(), $company);
        $dividendsX->setName('Получение дивидендов проекта от Х')->setParent($inv)->setCompany($company)->setSortOrder(90);
        $manager->persist($dividendsX);

        $manager->flush();

// --- Транзакции --- //
        $transactions = [
            ['2024-06-01', 100000.00, 'income', 'Выручка Озон', $revenueOzon, $bank],
            ['2024-06-02', 150000.00, 'income', 'Выручка ВБ', $revenueWb, $cash],
            ['2024-06-03', 20000.00, 'expense', 'Закупка материалов', $materials, $bank],
            ['2024-06-04', 15000.00, 'expense', 'Оплата подрядчиков', $contractors, $bank],
            ['2024-06-05', 20000.00, 'income', 'Получен кредит', $loansIncome, $bank],
            ['2024-06-06', 20000.00, 'expense', 'Погашение кредита', $loansPayment, $cash],
            ['2024-06-07', 3000.00, 'expense', 'Инвестиции в проект Х', $investX, $bank],
            ['2024-06-08', 15000.00, 'income', 'Дивиденды по проекту Х', $dividendsX, $bank],

            ['2024-05-01', 100000.00, 'income', 'Выручка Озон', $revenueOzon, $bank],
            ['2024-05-02', 150000.00, 'income', 'Выручка ВБ', $revenueWb, $cash],
            ['2024-05-03', 20000.00, 'expense', 'Закупка материалов', $materials, $bank],
            ['2024-05-04', 15000.00, 'expense', 'Оплата подрядчиков', $contractors, $bank],
            ['2024-05-05', 20000.00, 'income', 'Получен кредит', $loansIncome, $bank],
            ['2024-05-06', 20000.00, 'expense', 'Погашение кредита', $loansPayment, $cash],
            ['2024-05-07', 3000.00, 'expense', 'Инвестиции в проект Х', $investX, $bank],
            ['2024-05-08', 15000.00, 'income', 'Дивиденды по проекту Х', $dividendsX, $bank],
        ];

        foreach ($transactions as [$date, $amount, $direction, $comment, $category, $account]) {
            $transaction = new CashflowTransaction(Uuid::uuid4()->toString());
            $transaction->setDate(DateTimeImmutable::createFromInterface(new \DateTime($date)));
            $transaction->setAmount($amount);
            $transaction->setDirection($direction);
            $transaction->setComment($comment);
            $transaction->setCategory($category);
            $transaction->setCompany($company);
            $transaction->setAccount($account);
            $transaction->setProject($project);
            $manager->persist($transaction);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
            ProjectFixtures::class,
        ];
    }

}
