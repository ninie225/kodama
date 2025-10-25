<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarteController extends AbstractController
{
    #[Route('/carte', name: 'app_carte')]
    public function index(PlatRepository $repo): Response
    {
        $plats = $repo->findBy(['active' => true]);
        return $this->render('carte/index.html.twig', [
            'plats' => $plats,
        ]);
    }
}
