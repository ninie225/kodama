<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CommandeController extends AbstractController
{
    #[IsGranted('ROLE_CHEF')]
    #[Route('/dashboard/commandes', name: 'app_commandes')]
    public function index(CommandeRepository $repo): Response
    {
        $commandes= $repo->findBy([], ['date_commande' => 'DESC']);
        return $this->render('dashboard/commandes.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/dashboard/commande/{id}', name: 'app_commande_detail')]
    public function details(CommandeRepository $repo, int $id): Response
    {
        $commande = $repo->find($id);
        if (!$commande) {
            throw $this->createNotFoundException('Commande introuvable');
        }
        return $this->render('dashboard/commande_detail.html.twig', [
            'commande' => $commande,
        ]);
    }
}
