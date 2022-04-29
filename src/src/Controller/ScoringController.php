<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\Type\ClientType;
use App\Service\ScoringService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoringController extends AbstractController
{
    #[Route('/', name: 'scoring_home')]
    public function index(): Response
    {
        return $this->render('scoring/home.html.twig');
    }

    #[Route('/register', name: 'scoring_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, ScoringService $scoringService): Response
    {
        $form = $formFactory->createNamed('register', ClientType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();
            $score = $scoringService->calcClientScore($client);
            $client->scoring($score);

            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash('success', 'form.success.register');

            return $this->redirectToRoute('scoring_home');
        }

        return $this->renderForm('scoring/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/list', name: 'scoring_list')]
    public function list(Request $request, EntityManagerInterface $entityManager): Response
    {
        $page = $request->query->get('page', 1);
        $limit = 20;
        $paginator = $entityManager->getRepository(Client::class)->findAllWithScoreByPage($page, $limit);
        $total = $paginator->count();
        $pages = ceil($total / $limit);
        $clients = $paginator->getIterator();

        return $this->render('scoring/list.html.twig', [
            'page' => $page,
            'pages' => $pages,
            'clients' => $clients,
        ]);
    }

    #[Route('/edit/{id}', name: 'scoring_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, FormFactoryInterface $formFactory, int $id): Response
    {
        $client = $entityManager->getRepository(Client::class)->find($id);

        if (!$client) {
            $this->addFlash('error', 'form.error.not_found');
            return $this->redirectToRoute('scoring_home');
        }

        $form = $formFactory->createNamed('edit', ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->getData();
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('scoring_list');
        }

        return $this->renderForm('scoring/edit.html.twig', [
            'form' => $form,
            'client' => $client,
        ]);
    }
}
