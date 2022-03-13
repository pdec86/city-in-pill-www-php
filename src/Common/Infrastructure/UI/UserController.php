<?php

namespace App\Common\Infrastructure\UI;

use App\Common\Application\AddUserContactService;
use App\Common\Application\CreateNewUserService;
use App\Common\Application\Exception\UserNotFoundException;
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
        $body = $request->getContent(false);
        $bodyDecoded = json_decode($body, true);

        $username = $bodyDecoded['username'];
        $firstName = $bodyDecoded['first_name'];
        $lastName = $bodyDecoded['last_name'];
        $phone = array_key_exists('phone', $bodyDecoded) ? $bodyDecoded['phone'] : null;
        $email = array_key_exists('email', $bodyDecoded) ? $bodyDecoded['email'] : null;

        try {
            $service->execute($username, $firstName, $lastName, $phone, $email);
        } catch (UserNotFoundException $e) {
            $response = [
                'error' => 'USER_100',
                'message' => 'User not found'
            ];
            return new Response(json_encode($response), 400);
        }

        return new Response('{}', 201);
    }
}
