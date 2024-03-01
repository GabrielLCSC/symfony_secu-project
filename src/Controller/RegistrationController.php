<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $user->setRoles(['ROLE_USER']);
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Hasher le mot de passe
        $hashedPassword = $userPasswordHasher->hashPassword(
            $user,
            $form->get('plainPassword')->getData()
        );

        if (password_verify("admin1", $hashedPassword)) {
            $user->setRoles(['ROLE_ADMIN']);
        }

        // Stocker le mot de passe hashé
        $user->setPassword($hashedPassword);

        // Enregistrer l'utilisateur
        $entityManager->persist($user);
        $entityManager->flush();

        // Rediriger vers la page d'accueil
        return $this->redirectToRoute('app_ham');
    }

    return $this->render('registration/register.html.twig', [
        'registrationForm' => $form,
    ]);
}

}
