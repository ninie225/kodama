<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Form\PlatType;
use App\Repository\PlatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(PlatRepository $repo): Response
    {
        $plats = $repo->findAll();

        return $this->render('dashboard/index.html.twig', [
            'plats' => $plats,
        ]);
    }

    #[Route('/toggle/{id}', name: 'app_dashboard_toggle')]
    public function toggleActive(Plat $plat, EntityManagerInterface $em): Response
    {
        $plat->setActive(!$plat->isActive());
        $em->flush();

        $this->addFlash('success', $plat->isActive()
            ? 'Le plat a été activé.'
            : 'Le plat a été désactivé.');

        return $this->redirectToRoute('app_dashboard');
    }

    #[Route('/nouveau_plat', name: 'app_nouveau_plat')]
    #[IsGranted('ROLE_CHEF')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $plat = new Plat();
        $form = $this->createForm(PlatType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('photo')->getData();

            if ($uploadedFile) {
                // Nom du fichier modifié pour prendre le nom du plat
                $nomPlat = preg_replace('/[^a-zA-Z0-9-_]/', '_', strtolower($plat->getNom()));
                $extension = $uploadedFile->guessExtension() ?: 'jpg';
                $fileName = $nomPlat . '.' . $extension;

                // Dossier de destination 
                $uploadDir = $this->getParameter('kernel.project_dir') . '/assets/images/uploads/carte/';

                $uploadedFile->move($uploadDir, $fileName);
                $plat->setPhoto('images/uploads/carte/' . $fileName);
            }

            $em->persist($plat);
            $em->flush();

            $this->addFlash('success', 'Le plat "' . $plat->getNom() . '" a été ajouté avec succès !');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('dashboard/nouveauplat.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
