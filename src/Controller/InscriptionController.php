<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ValidationType;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(Request $request, MailerInterface $mailer, EntityManagerInterface $manager, UtilisateurRepository $repo): Response 
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(InscriptionType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $existingUser = $repo->findOneBy(['email' => $utilisateur->getEmail()]);

            if ($existingUser) {
                $this->addFlash('error', 'Un compte existe déjà avec cette adresse e-mail.');
                return $this->redirectToRoute('app_inscription');
            }

            $token = uniqid();
            $utilisateur->setRoles(['ROLE_CLIENT']);
            $utilisateur->setToken($token);
            $utilisateur->setPassword('provisoire'); 

            $manager->persist($utilisateur);
            $manager->flush();

            // Envoi du mail de validation
            $email = (new TemplatedEmail())
                ->from('nepasrepondre@kodama.com')
                ->to($utilisateur->getEmail())
                ->subject('Validation de votre compte')
                ->htmlTemplate('emails/validation.html.twig')
                ->context(['token' => $token]);

            $mailer->send($email);

            $this->addFlash('success', 'Consultez votre boîte mail pour valider votre compte.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('inscription/compte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/validation/{token}', name: 'app_validation')]
    public function validation(UserPasswordHasherInterface $hasher, UtilisateurRepository $repo, Request $request, EntityManagerInterface $manager, string $token): Response
    {
        $user = $repo->findOneBy(["token" => $token]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur inconnu');
        }

        $form = $this->createForm(ValidationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pwd = $form->get('password')->getData();
            $pwd_hash = $hasher->hashPassword($user, $pwd);
            $user->setPassword($pwd_hash);
            $user->setToken(null);

            $manager->flush();

            $this->addFlash('success', 'Votre compte est activé !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('inscription/validation.html.twig', [
            "form" => $form->createView(),
        ]);
    }
}
