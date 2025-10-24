<?php

namespace App\Controller;

use App\Form\ValidationType;
use App\Form\ForgotPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response 
    {
        // Gestion de la mise à jour du profil
        if ($request->isMethod('POST') && $this->getUser()) {
            return $this->updateProfile($request, $entityManager, $passwordHasher);
        }

        // Gestion de l'affichage
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'is_logged_in' => $this->getUser() !== null,
            'user' => $this->getUser(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/profil/update', name: 'app_profile_update', methods: ['POST'])]
    public function updateProfile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response 
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $telephone = $request->request->get('telephone');
        $adresse = $request->request->get('adresse');
        $cp = $request->request->get('cp');
        $ville = $request->request->get('ville');
        $password = $request->request->get('password');

        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setTelephone($telephone);
        $user->setAdresse($adresse);
        $user->setCp($cp);
        $user->setVille($ville);

        if ($password) {
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
        }

        $em->flush();

        $this->addFlash('success', 'Profil mis à jour avec succès !');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/mot-de-passe-oublie', name: 'app_forgot_password_request')]
    public function forgotPasswordRequest(Request $request, UtilisateurRepository $repo,EntityManagerInterface $manager, MailerInterface $mailer): Response 
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $email = $form->get('email')->getData();
            $user = $repo->findOneBy(['email' => $email]);

            if ($user) {
                $token = uniqid();
                $user->setToken($token);
                $manager->flush();

                $email = (new TemplatedEmail())
                    ->from('nepasrepondre@kodama.com')
                    ->to($user->getEmail())
                    ->subject('Réinitialisation de votre mot de passe')
                    ->htmlTemplate('emails/forgot_password.html.twig')
                    ->context(['token' => $token]);
                $mailer->send($email);

                $this->addFlash('success', 'Un email de réinitialisation vous a été envoyé.');
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('danger', 'Aucun compte trouvé avec cette adresse email.');
                return $this->redirectToRoute('app_forgot_password_request');
            }
        }


        return $this->render('security/motdepasse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reinitialisation-mot-de-passe/{token}', name: 'app_reset_password')]
    public function resetPassword( $token, Request $request, UtilisateurRepository $repo, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager ): Response 
    {
        $user = $repo->findOneBy(['token' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur inconnu ou token invalide.');
        }

        $form = $this->createForm(ValidationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) 
        {
            if ($form->isValid()) {
                $pwd = $form->get('password')->getData();
                $pwd_hash = $hasher->hashPassword($user, $pwd);
                $user->setPassword($pwd_hash);
                $user->setToken(null);
                $manager->flush();
                $this->addFlash('success', 'Votre mot de passe a été réinitialisé !');
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('error', 'Les mots de passe doivent être identiques et faire plus de 12 caractères.');
                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }   
        }

        return $this->render('security/reset_mdp.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}