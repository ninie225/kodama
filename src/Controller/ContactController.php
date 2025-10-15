<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();

            $objet=$data['objet'];
            $email = $data['email'];
            $phone = $data['telephone'];
            $msg = $data['message'];

            $mail=(new Email())
                ->from($email)
                ->to('me@example.com')
                ->subject($objet)
                ->html('Message de : '.$email.' Téléphone : '.$phone.' Message : '.$msg);

            try {
                $mailer->send($mail);
                // Ajout d'un message flash pour indiquer que le message a été envoyé avec succès
                $this->addFlash('success', 'Votre message a été envoyé avec succès !');

            } 
            catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi du message : ' . $e->getMessage());
            }

            // Redirigez vers une autre page
            return $this->redirectToRoute('app_contact');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
