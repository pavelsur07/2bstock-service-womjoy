<?php

declare(strict_types=1);

namespace App\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\CashflowCategory;
use App\Form\CashflowCategoryType;
use App\Repository\CashflowCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class CashflowCategoryController extends AbstractController
{
    #[Route('/finance/cashflow/categories', name: 'cashflow_category_index')]
    public function index(CashflowCategoryRepository $repository): Response
    {
        //$categories = $repository->findBy(['company' => $this->getUser()->getCompanies()[0]]);
        $categories = $repository->listByCompanyId($this->getUser()->getCompanies()[0]->getId());
        return $this->render('finance/cashflow/categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/finance/cashflow/categories/new', name: 'cashflow_category_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $category = new CashflowCategory(id: Uuid::uuid4()->toString(),company: $this->getUser()->getCompanies()[0]);
        $form = $this->createForm(CashflowCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$category->setCompany($this->getUser()->getCompanies()[0]);
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('cashflow_category_index');
        }

        return $this->render('finance/cashflow/categories/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/finance/cashflow/categories/{id}/edit', name: 'cashflow_category_edit')]
    public function edit(CashflowCategory $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CashflowCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('cashflow_category_index');
        }

        return $this->render('finance/cashflow/categories/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
