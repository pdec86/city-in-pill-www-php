<?php

namespace App\Tests\Common\Infrastructure\UI;

use App\Common\Application\CreateNewUserService;
use App\Common\Infrastructure\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    public function testGetAddUser()
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $client->request('GET', '/en/sign_up');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label[for=username]', 'Username');
        $this->assertSelectorTextContains('label[for=password]', 'Password');
    }

    public function testPostAddUser()
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/sign_up');
        $csrfToken = $crawler->filter('input[name=_csrf_token]')->attr('value');

        // Request a specific page
        $client->request(
            'POST',
            '/en/sign_up',
            [
                'username' => 'Przemek',
                'password' => 'Pawo',
                '_csrf_token' => $csrfToken],
            [],
            ['CONTENT_TYPE' => 'application/x-www-form-urlencoded']);

        // Validate a successful response and some content
        $this->assertResponseRedirects('/en/login', 302);
    }

    public function testPostAddUserInvalidCsrfToken()
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $client->request(
            'POST',
            '/en/sign_up',
            [
                'username' => 'Przemek',
                'password' => 'Pawo',
                '_csrf_token' => ''],
            [],
            ['CONTENT_TYPE' => 'application/x-www-form-urlencoded']);

        // Validate a successful response and some content
        $this->assertResponseStatusCodeSame(200);
    }

    public function testAddUserContact()
    {
        // (1) This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);
        /** @var CreateNewUserService $service */
        $service = $container->get(CreateNewUserService::class);
        $service->execute('user', 'somepass1');

        $testUser = $userRepository->loadUserByIdentifier('user');
        $this->assertNotEmpty($testUser);

        $client->loginUser($testUser);

        $bodyJson = json_encode([
            'username' => 'user',
            'first_name' => 'Przemek',
            'last_name' => 'Przemek',
            'phone' => '+48 500 100 200',
            'email' => 'pp@mail.com']);

        $bodyInvalidUserJson = json_encode([
            'username' => 'user1',
            'first_name' => 'Przemek',
            'last_name' => 'Przemek',
            'phone' => '+48 500 100 200',
            'email' => 'pp@mail.com']);

        // Request a specific page
        $client->request('POST', '/api/user/contact', [], [], ['CONTENT_TYPE' => 'application/json'], $bodyJson);
        $this->assertResponseStatusCodeSame(201);

        // Request a specific page
        $client->request('POST', '/api/user/contact', [], [], ['CONTENT_TYPE' => 'application/json'], $bodyJson);
        $this->assertResponseStatusCodeSame(201);

        // Request a specific page
        $client->request('POST', '/api/user/contact', [], [], ['CONTENT_TYPE' => 'application/json'], $bodyInvalidUserJson);
        $this->assertResponseStatusCodeSame(400);
    }

    public function testAddUserContactNoGet()
    {
        // (1) This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $client->request('GET', '/api/user/contact');
        $this->assertResponseStatusCodeSame(405);
    }
}
