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
        // --- Счет и Касса --- //

        $bank = new CashAccount(Uuid::uuid4()->toString());
        $bank->setCompany($company);
        $bank->setName('Банковский счет основной');
        $bank->setType(CashAccount::BANK);
        $bank->setOpeningBalance(100000);
        $manager->persist($bank);

        $cash = new CashAccount(Uuid::uuid4()->toString());
        $cash->setCompany($company);
        $cash->setName('Касса основная');
        $cash->setType(CashAccount::CASH);
        $cash->setOpeningBalance(50000);
        $manager->persist($cash);

        // --- Категории --- //
        $categories = [];

        $revenue = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $revenue->setName('Выручка');
        $revenue->setCompany($company);
        $revenue->setSortOrder(1);
        $manager->persist($revenue);

        $revenueWb = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $revenueWb->setName('Валбериз')->setParent($revenue)->setCompany($company);
        $revenueWb->setSortOrder(10);
        $manager->persist($revenueWb);

        $revenueOzon = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $revenueOzon->setName('Озон')->setParent($revenue)->setCompany($company);
        $revenueOzon->setSortOrder(20);
        $manager->persist($revenueOzon);

        $production = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $production->setName('Производство и себестоимость')->setCompany($company);
        $production->setSortOrder(2);
        $manager->persist($production);

        $materials = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $materials->setName('Материалы')->setParent($production)->setCompany($company);
        $materials->setSortOrder(30);
        $manager->persist($materials);

        $contractors = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $contractors->setName('Подрядчики')->setParent($production)->setCompany($company);
        $contractors->setSortOrder(40);
        $manager->persist($contractors);

        $finance = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $finance->setName('Финансовая деятельность')->setCompany($company);
        $finance->setSortOrder(3);
        $manager->persist($finance);

        $loans = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $loans->setName('Выплата кредитов и займов')->setParent($finance)->setCompany($company);
        $loans->setSortOrder(50);
        $manager->persist($loans);

        $invest = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $invest->setName('Инвестиционная деятельность')->setCompany($company);
        $invest->setSortOrder(4);
        $manager->persist($invest);

        $projectX = new CashflowCategory(Uuid::uuid4()->toString(),$company);
        $projectX->setName('Новый проект Х')->setParent($invest)->setCompany($company);
        $projectX->setSortOrder(60);
        $manager->persist($projectX);

        $manager->flush();

        // --- Транзакции --- //
        $transactions = [

            ['2024-04-01', 10000.00, 'income', 'Поступление с Валбериз', $revenueWb, $bank],
            ['2024-04-03', 8000.00, 'income', 'Поступление с Озон', $revenueOzon, $cash],
            ['2024-04-05', 4000.00, 'expense', 'Покупка ткани и фурнитуры', $materials, $bank],
            ['2024-04-06', 2000.00, 'expense', 'Оплата пошива', $contractors, $bank],
            ['2024-04-10', 3000.00, 'expense', 'Погашение займа', $loans, $cash],
            ['2024-04-15', 10000.00, 'expense', 'Инвестиции в новый проект', $projectX, $bank],

            ['2024-05-01', 120000.00, 'income', 'Поступление с Валбериз', $revenueWb, $bank],
            ['2024-05-03', 85000.00, 'income', 'Поступление с Озон', $revenueOzon, $cash],
            ['2024-05-05', 40000.00, 'expense', 'Покупка ткани и фурнитуры', $materials, $bank],
            ['2024-05-06', 25000.00, 'expense', 'Оплата пошива', $contractors, $bank],
            ['2024-05-10', 30000.00, 'expense', 'Погашение займа', $loans, $cash],
            ['2024-05-15', 100000.00, 'expense', 'Инвестиции в новый проект', $projectX, $bank],
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
