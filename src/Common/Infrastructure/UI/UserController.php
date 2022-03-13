<?php

namespace App\Common\Infrastructure\UI;

use App\Common\Application\AddUserContactService;
use App\Common\Application\CreateNewUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class UserController extends AbstractController
{
    /**
     * @Route(path="/{_locale}/sign_up", name="user_signup", methods={"GET", "POST"}, requirements={"_locale": "en|pl"})
     */
    public function addUser(Request $request, CsrfTokenManagerInterface $csrfTokenManager, CreateNewUserService $service): Response
    {
        if ($request->isMethod("POST")) {
            $username = $request->get('username');
            $password = $request->get('password');
            $csrfToken = new CsrfToken('signup', $request->get('_csrf_token'));

            if (!$csrfTokenManager->isTokenValid($csrfToken)) {
                return $this->render('user/sign_up.html.twig', [
                    'error' => 'signup.invalid.token'
                ]);
            }

            $service->execute($username, $password);
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/sign_up.html.twig', [
            'error' => null
        ]);
    }

    /**
     * @Route(path="/api/user/contact", name="api_user_contact", methods={"POST"})
     */
    public function addUserContact(Request $request, AddUserContactService $service): Response
    {
        $username = $request->get('username');
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $phone = $request->get('phone', null);
        $email = $request->get('email', null);
        $service->execute($username, $firstName, $lastName, $phone, $email);

        return new Response('{}', 201);
    }
}
