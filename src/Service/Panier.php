<?php

namespace App\Service;

use App\Entity\Plat;
use App\Repository\PlatRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Panier
{
    private RequestStack $requestStack;
    private PlatRepository $repo;

    public function __construct(RequestStack $requestStack, PlatRepository $repo)
    {
        $this->requestStack = $requestStack;
        $this->repo = $repo;
    }

    private function getSession(): ?SessionInterface
    {
        $session = $this->requestStack->getSession();

        if (!$session) {
            return null;
        }

        if (!$session->isStarted()) {
            try {
                $session->start();
            } catch (\Throwable $e) {
                return null;
            }
        }

        return $session;
    }

    public function add(Plat $plat, int $quantity = 1): void
    {
        $session = $this->getSession();
        if (!$session) {
            return;
        }

        $panier = $session->get("panier", []);
        $id = $plat->getId();

        if (isset($panier[$id])) {
            $panier[$id] = $panier[$id] + $quantity;
        } else {
            $panier[$id] = $quantity;
        }

        $session->set("panier", $panier);
    }

    /**
     * Définit la quantité exacte pour un plat (remplace la valeur précédente).
     * Si quantity <= 0 : supprime l'entrée.
     */
    public function set(Plat $plat, int $quantity): void
    {
        $session = $this->getSession();
        if (!$session) {
            return;
        }

        $panier = $session->get('panier', []);
        $id = $plat->getId();

        if ($quantity <= 0) {
            if (isset($panier[$id])) {
                unset($panier[$id]);
            }
        } else {
            $panier[$id] = $quantity;
        }

        $session->set('panier', $panier);
    }

    public function del(Plat $plat): void
    {
        $session = $this->getSession();
        if (!$session) {
            return;
        }

        $panier = $session->get("panier", []);

        $id = $plat->getId();
        if (isset($panier[$id])) {
            $panier[$id]--;
            if ($panier[$id] <= 0) {
                unset($panier[$id]);
            }
        }

        $session->set("panier", $panier);
    }

    public function get(): array
    {
        $session = $this->getSession();
        if (!$session) {
            return [];
        }

        $panier = $session->get("panier", []);

        $panierPourTwig = [];
        foreach ($panier as $idPlat => $quantite) {
            $plat = $this->repo->find($idPlat);
            if ($plat) {
                $panierPourTwig[] = [
                    'plat' => $plat,
                    'quantite' => $quantite,
                ];
            }
        }

        return $panierPourTwig;
    }

    public function getTotalItems(): int
    {
        $session = $this->getSession();
        if (!$session) {
            return 0;
        }

        $panier = $session->get('panier', []);
        if (!is_array($panier) || empty($panier)) {
            return 0;
        }

        return array_sum($panier);
    }
}
