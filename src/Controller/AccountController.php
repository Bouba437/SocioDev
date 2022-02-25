<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegisterType;
use App\Entity\PasswordUpdate;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Permet de s'inscrire sur le site
     * 
     * @Route("/inscription", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordHasherInterface $hasher) : Response {
        $user = new User();
        $date = new DateTime();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash)
                ->setCreatedAt($date);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash("success", "Votre inscription a été validé avec succès !");

            return $this->redirectToRoute('home');
        }

        return $this->render('account/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet à l'utilisateur de se connecter
     * 
     * @Route("/connexion", name="account_login")
     *
     * @return Response
     * 
     */
    public function login(AuthenticationUtils $utils) {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
        ]);
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/deconnexion", name="account_logout")
     *
     * @return void
     */
    public function logout() {

    }

    /**
     * Permet d'afficher les informations du profil
     * 
     * @Route("/compte", name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return void
     */
    public function myAccount() {
        $user = $this->getUser();

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Permet de modifier les informations d'un utilisateur
     * 
     * @Route("/compte/modifier-mon-profil", name="account_profile")
     *
     * @return Response
     */
    public function profile (Request $request) {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash("success", "Vos informations personnelles ont été bien modifiées.");

            return $this->redirectToRoute('account_profile');
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Permet de mettre à jour le mot de passe de l'utilisateur en cours
     * 
     * @Route("compte/modifier-mon-mot-de-passe", name="account_password_update")
     *
     * @return void
     */
    public function PasswordUpdate(Request $request, UserPasswordHasherInterface $hasher) {
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez saisi n'est pas votre mot de passe actuel."));
            } else {
                $hash = $hasher->hashPassword($user, $passwordUpdate->getNewPassword());
                $user->setPassword($hash);

                $this->entityManager->flush();

                $this->addFlash("success", "Votre mot de passe a bien été modifié");

                return $this->redirectToRoute('home');
            }
        }

        return $this->render('account/password_update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

