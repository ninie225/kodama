<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Detail;
use App\Form\AdresseType;
use App\Service\Panier;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('/checkout/adresse', name: 'app_checkout_adresse')]
    public function adresse(Request $request): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour poursuivre la commande.');
            return $this->redirectToRoute('app_login');
        }

        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();

        $form = $this->createForm(AdresseType::class, [
            'adresse' => $user->getAdresse(),
            'cp' => $user->getCp(),
            'ville' => $user->getVille(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setAdresse($data['adresse']);
            $user->setCp($data['cp']);
            $user->setVille($data['ville']);

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('app_checkout_paiement');
        }

        return $this->render('checkout/adresse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/checkout/paiement', name: 'app_checkout_paiement')]
    public function paiement(Request $request, Panier $panierService, MailerInterface $mailer): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('warning', 'Vous devez être connecté pour valider la commande.');
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les éléments du panier
        $items = $panierService->get();
        if (empty($items)) {
            $this->addFlash('info', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier');
        }

        $total = 0;
        foreach ($items as $item) {
            $total += $item['plat']->getPrix() * $item['quantite'];
        }

        // Gestion du POST (paiement ou validation)
        if ($request->isMethod('POST')) {
            $method = $request->request->get('payment_method', 'card');
            $user = $this->getUser();

            $errors = [];

            // Si paiement par carte : vérif
            if ($method === 'card') {
                $cardNumber = str_replace(' ', '', $request->request->get('card_number', ''));
                $cardName = trim($request->request->get('card_name', ''));
                $cardExp = trim($request->request->get('card_exp', ''));
                $cardCvv = trim($request->request->get('card_cvv', ''));

                if ($cardName === '') {
                    $errors[] = 'Le nom sur la carte est requis.';
                }
                if (!preg_match('/^\d{16}$/', $cardNumber)) {
                    $errors[] = 'Le numéro de carte doit contenir 16 chiffres.';
                }
                if (!preg_match('/^\d{2}\/\d{2}$/', $cardExp)) {
                    $errors[] = 'La date d\'expiration doit être au format MM/AA.';
                }
                if (!preg_match('/^\d{3,4}$/', $cardCvv)) {
                    $errors[] = 'Le code CVV doit comporter 3 ou 4 chiffres.';
                }
            }

            // Si erreurs : réafficher avec messages
            if (count($errors) > 0) {
                foreach ($errors as $e) {
                    $this->addFlash('danger', $e);
                }

                return $this->render('checkout/paiement.html.twig', [
                    'items' => $items,
                    'total' => $total,
                    'payment_method' => $method,
                ]);
            }

            // Création de la commande
            $commande = new Commande();
            $commande->setUtilisateur($user);
            $commande->setDateCommande(new DateTime());
            $commande->setEtat(0); 
            $commande->setTotal($total + 4);

            $this->em->persist($commande);

            // Détails de la commande
            foreach ($items as $it) {
                $detail = new Detail();
                $detail->setCommande($commande);
                $detail->setPlat($it['plat']);
                $detail->setQuantite($it['quantite']);
                $this->em->persist($detail);
            }

            $this->em->flush();

            // Envoi email confirmation
            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@kodama.com', 'Kodama\'s Kitchen'))
                ->to($user->getEmail())
                ->subject('Confirmation de votre commande #' . $commande->getId())
                ->htmlTemplate('emails/commande_confirmation.html.twig')
                ->context([
                    'user' => $user,
                    'commande' => $commande,
                    'items' => $items,
                    'total' => $total + 4,
                    'payment_method' => $method,
                ]);

            $mailer->send($email);

            // Vider le panier
            $request->getSession()->remove('panier');

            // Rediriger vers confirmation
            return $this->redirectToRoute('app_checkout_confirmation', [
                'id' => $commande->getId()
            ]);
        }

        // GET : afficher la page paiement
        return $this->render('checkout/paiement.html.twig', [
            'items' => $items,
            'total' => $total,
            'payment_method' => null,
        ]);
    }

    #[Route('/checkout/confirmation/{id}', name: 'app_checkout_confirmation')]
    public function confirmation(int $id): Response
    {
        $commande = $this->em->getRepository(Commande::class)->find($id);
        if (!$commande) {
            $this->addFlash('danger', 'Commande introuvable.');
            return $this->redirectToRoute('app_carte');
        }

        return $this->render('checkout/confirmation.html.twig', [
            'commande' => $commande,
        ]);
    }
}
