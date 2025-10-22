<?php

// src/Controller/DetailsController.php
namespace App\Controller;

use App\Entity\Plat;
use App\Service\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailsController extends AbstractController
{
    #[Route('/details/{id}', name: 'app_details')]
    public function index(Plat $plat, Request $request, Panier $panier): Response
    {
        $session = $request->getSession();
        $quantities = $session->get('panier', []);
        $quantityInCart = $quantities[$plat->getId()] ?? 0;

        if ($request->isMethod('POST')) {
            $newQuantity = max(0, (int) $request->request->get('quantity', 0));
            $panier->set($plat, $newQuantity);

            // redirige pour éviter le repost du formulaire
            return $this->redirectToRoute('app_details', ['id' => $plat->getId()]);
        }

        return $this->render('details/index.html.twig', [
            'plat' => $plat,
            'quantityInCart' => $quantityInCart,
        ]);
    }
}
