<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_CLIENT')]
final class ClientController extends AbstractController
{
    #[Route('/mon-compte/commandes', name: 'app_commandes_client')]
    public function commandes(): Response
    {
        $user = $this->getUser();

        return $this->render('client/commandes.html.twig', [
            'commandes' => $user->getCommandes(),
        ]);
    }

    #[Route('/mon-compte/commande/{id}', name: 'app_commande_client_detail')]
    public function commandeDetail(Commande $commande): Response
    {
        $user = $this->getUser();

        // Vérifie que la commande appartient bien à l'utilisateur connecté
        if ($commande->getUtilisateur() !== $user) {
            throw $this->createAccessDeniedException('Accès non autorisé à cette commande.');
        }

        return $this->render('client/commande_detail.html.twig', [
            'commande' => $commande,
        ]);
    }

}
