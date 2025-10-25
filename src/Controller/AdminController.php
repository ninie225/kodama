<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/dashboard/utilisateurs', name: 'app_dashboard_utilisateurs')]
    public function index(UtilisateurRepository $repo): Response
    {
        $utilisateurs = $repo->findAll();

        return $this->render('dashboard/utilisateurs.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'app_dashboard_utilisateur_supprimer')]
    public function supprimer(Utilisateur $utilisateur, EntityManagerInterface $em): Response
    {
        if ($utilisateur === $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre propre compte.');
            return $this->redirectToRoute('app_dashboard_utilisateurs');
        }
        $em->remove($utilisateur);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès.');

        return $this->redirectToRoute('app_dashboard_utilisateurs');
    }
}
