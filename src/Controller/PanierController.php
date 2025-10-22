<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Service\Panier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(Panier $panier): Response
    {
        $panierItems = $panier->get();
        $totalItems = array_reduce($panierItems, fn($carry, $item) => $carry + $item['quantite'], 0);
        return $this->render('panier/index.html.twig', [
            'panier' => $panierItems,
            'totalItems' => $totalItems,
        ]);
    }
    
    #[Route('/panier/add/{plat}', name: 'app_add_panier')]
    public function panier_add(Plat $plat, Panier $panier, Request $request): Response
    {
        $quantity = $request->request->getInt('quantity', 1); 
        if ($quantity <= 0) {
            $quantity = 1;
        }
        $panier->add($plat, $quantity);
        return $this->redirect("/panier");
    }

    #[Route('/panier/del/{plat}', name: 'app_del_panier')]
    public function panier_del(Plat $plat, Panier $panier): Response
    {
        $panier->del($plat);

        return $this->redirect("/panier");
    }
}
