<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * Affiche et gère le formulaire de connexion pour l'administration
     * (Voir « security.yaml »)
     * 
     * @Route("/admin/login", name="admin_account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $username = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();


        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter depuis une connexion administrateur
     * (Voir « security.yaml ») 
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     * 
     * @return void 
     */
    public function logout()
    {
        // Rien...
    }
}
