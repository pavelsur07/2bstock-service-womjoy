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
    public function index(CashflowTransactionRepository $repository): Response
    {
        //$transactions = $repository->findBy(['company' => $this->getUser()->getCompanies()[0]]);
        $transactions = $repository->listByCompanyId($this->getUser()->getCompanies()[0]->getId());
        return $this->render('finance/cashflow/index.html.twig', [
            'transactions' => $transactions
        ]);
    }

    #[Route('/finance/cashflow/new', name: 'cashflow_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $transaction = new CashflowTransaction(Uuid::uuid4()->toString());
        $form = $this->createForm(CashflowTransactionType::class, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $transaction->setCompany($this->getUser()->getCompanies()[0]);
            $transaction->setDirection($data['direction']);
            $transaction->setAmount($data['amount']);
            $transaction->setDate(DateTimeImmutable::createFromInterface($data['date']));
            $transaction->setCategory($data['category']);
            $transaction->setComment($data['comment']);
            $transaction->setAccount($data['account']);
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
        $form = $this->createForm(CashflowTransactionType::class, [
            'direction' => $transaction->getDirection(),
            'amount' => $transaction->getAmount(),
            'category' => $transaction->getCategory(),
            'comment' => $transaction->getComment(),
            'date' => $transaction->getDate()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $transaction->setDirection($data['direction']);
            $transaction->setAmount($data['amount']);
            $transaction->setDate(DateTimeImmutable::createFromInterface($data['date']));
            $transaction->setCategory($data['category']);
            $transaction->setComment($data['comment']);
            $transaction->setAccount($data['account']);
            $em->flush();
            return $this->redirectToRoute('cashflow_index');
        }

        return $this->render('finance/cashflow/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
