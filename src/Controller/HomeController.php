<?php

namespace App\Controller;

use App\Common\Infrastructure\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route(path="/", name="dashboard")
     * @return Response
     */
    public function dashboard(): Response {
        if ($this->getUser() != null) {
            return $this->redirectToRoute('home');
        }
        return $this->render('base.html.twig');
    }

    /**
     * @Route(path="/home", name="home")
     * @return Response
     */
    public function home(): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('home.html.twig', [
            'username' => $this->getUser() != null ? $this->getUser()->getUsername() : 'anonymous',
            'roles' => []
        ]);
    }

    /**
     * @Route(path="/api/home", name="api_home")
     * @return Response
     */
    public function apiHome(UserRepository $userRepository): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('home.html.twig', [
            'username' => $this->getUser()->getUsername(),
            'roles' => $this->getUser()->getRoles()
        ]);
    }
}
