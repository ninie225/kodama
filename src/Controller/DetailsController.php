<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DetailsController extends AbstractController
{
    #[Route('/details/{id}', name: 'app_details')]
    public function index(PlatRepository $repo, int $id): Response
    {
        $plat= $repo->find($id);
        return $this->render('details/index.html.twig', [
            'plat' => $plat,
        ]);
    }
}
