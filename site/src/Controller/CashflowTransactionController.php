<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\CashflowTransaction;
use App\Form\CashflowTransactionType;
use App\Repository\CashflowTransactionRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CashflowTransactionController extends AbstractController
{
    #[Route('/finance/cashflow', name: 'cashflow_index')]
    public function index(Request $request, CashflowTransactionRepository $repository): Response
    {
        $page = max(1, (int)$request->query->get('page', 1));
        $perPage = 10;
        $companyId = $this->getUser()->getCompanies()[0]->getId();

        $transactions = $repository->paginateByCompanyId($companyId, $page, $perPage);
        $total = $repository->countByCompanyId($companyId);
        $pages = (int)ceil($total / $perPage);

        return $this->render('finance/cashflow/index.html.twig', [
            'transactions' => $transactions,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    #[Route('/finance/cashflow/new', name: 'cashflow_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $transaction = new CashflowTransaction(Uuid::uuid4()->toString());
        $form = $this->createForm(CashflowTransactionType::class,$transaction,
            [
                'company' => $this->getUser()->getCompanies()[0]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $transaction->setCompany($this->getUser()->getCompanies()[0]);
            $em->persist($transaction);
            $em->flush();
            return $this->redirectToRoute('cashflow_index');
        }

        return $this->render('finance/cashflow/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/finance/cashflow/{id}/edit', name: 'cashflow_edit')]
    public function edit(CashflowTransaction $transaction, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CashflowTransactionType::class, $transaction, [
            'company' => $this->getUser()->getCompanies()[0]
        ],);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute('cashflow_index');
        }

        return $this->render('finance/cashflow/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
