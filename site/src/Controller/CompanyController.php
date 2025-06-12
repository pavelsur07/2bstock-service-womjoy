<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'company_index')]
    public function index(): Response
    {
        $companies = $this->getUser()->getCompanies();

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/new', name: 'company_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $company = new Company(
            Uuid::uuid4()->toString(),
            '',
            $this->getUser(),
            new \DateTimeImmutable()
        );

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('company/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'company_edit')]
    public function edit(Company $company, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('company/edit.html.twig', [
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{id}/delete', name: 'company_delete')]
    public function delete(Company $company, EntityManagerInterface $em): Response
    {
        $em->remove($company);
        $em->flush();

        return $this->redirectToRoute('company_index');
    }
}
