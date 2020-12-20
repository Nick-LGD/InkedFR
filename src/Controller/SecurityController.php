<?php

namespace App\Controller;

use App\Repository\MoreRepository;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param MoreRepository $moreRepository
     * @param Request $request
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils, MoreRepository $moreRepository,Request $request): Response
    {
        if (!empty($this->getUser())) {
            $roles = $this->getUser()->getRoles();
            foreach($roles as $role){
                if($role === 'ROLE_USER'){
                    return $this->redirectToRoute('home');
                }
                if($role === 'ROLE_TATOUEUR'){
                    return $this->redirectToRoute('home');
                }
                if($role === 'ROLE_ADMIN'){
                    return $this->redirectToRoute('easyadmin');
                }
            }
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'mores'=> $moreRepository->findAll(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
