<?php

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\CashAccount;
use App\Form\CashAccountType;
use App\Repository\CashAccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/finance/accounts')]
class CashAccountController extends AbstractController
{
    #[Route('/', name: 'cash_account_index')]
    public function index(CashAccountRepository $repository): Response
    {
        $accounts = $repository->listByCompanyId($this->getUser()->getCompanies()[0]->getId());
        return $this->render('cash_account/index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    #[Route('/new', name: 'cash_account_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $account = new CashAccount(
            id:Uuid::uuid4()->toString(),
        );
        $account->setCompany($this->getUser()->getCompanies()[0]);

        $form = $this->createForm(CashAccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($account);
            $em->flush();
            return $this->redirectToRoute('cash_account_index');
        }

        return $this->render('cash_account/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'cash_account_edit')]
    public function edit(Request $request, CashAccount $account, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CashAccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('cash_account_index');
        }

        return $this->render('cash_account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
