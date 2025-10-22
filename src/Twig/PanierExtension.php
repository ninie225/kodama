<?php

namespace App\Twig;

use App\Service\Panier;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class PanierExtension extends AbstractExtension implements GlobalsInterface
{
    private Panier $panier;

    public function __construct(Panier $panier)
    {
        $this->panier = $panier;
    }

    public function getGlobals(): array
    {
        return [
            'totalItems' => $this->panier->getTotalItems(),
        ];
    }
}
