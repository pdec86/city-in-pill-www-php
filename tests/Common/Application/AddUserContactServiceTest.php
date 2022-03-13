<?php

namespace App\Tests\Common\Application;

use App\Common\Application\AddUserContactService;
use App\Common\Application\CreateNewUserService;
use App\Common\Infrastructure\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddUserContactServiceTest extends KernelTestCase
{
    /**
     * @var CreateNewUserService
     */
    private static $serviceCreateUser;

    /**
     * @var AddUserContactService
     */
    private static $serviceAddContact;

    /**
     * @var UserRepository
     */
    private static $userRepository;

    public static function setUpBeforeClass(): void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        self::$serviceCreateUser = $container->get(CreateNewUserService::class);
        self::$serviceAddContact = $container->get(AddUserContactService::class);
        self::$userRepository = $container->get(UserRepository::class);
    }

    public function testExecute()
    {
        $username = "przemo1";
        $firstName = "Przemysław";
        $lastName = "Pawo";
        $phone = "+48 500 100 200";
        $email = "pp@mail.com";
        self::$serviceCreateUser->execute($username, "pass1");

        $user = self::$userRepository->loadUserByIdentifier($username);
        self::$serviceAddContact->execute($username, $firstName, $lastName, $phone, $email);
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($firstName, $user->getContact()->getFirstName());
        $this->assertEquals($lastName, $user->getContact()->getLastName());
        $this->assertEquals($phone, $user->getContact()->getPhone()->getValue());
        $this->assertEquals($email, $user->getContact()->getEmail()->getValue());
    }

    public function testNotValidPhone()
    {
        $this->expectException(\RuntimeException::class);

        $username = "przemo1";
        $firstName = "Przemysław";
        $lastName = "Pawo";
        $phone = "+48 500-100-200";
        $email = "pp@mail.com";
        self::$serviceCreateUser->execute($username, "pass1");
        self::$userRepository->loadUserByIdentifier($username);
        self::$serviceAddContact->execute($username, $firstName, $lastName, $phone, $email);
    }

    public function testNotValidEmail()
    {
        $this->expectException(\RuntimeException::class);

        $username = "przemo1";
        $firstName = "Przemysław";
        $lastName = "Pawo";
        $phone = "+48 500 100 200";
        $email = "pp@mail";
        self::$serviceCreateUser->execute($username, "pass1");
        self::$userRepository->loadUserByIdentifier($username);
        self::$serviceAddContact->execute($username, $firstName, $lastName, $phone, $email);
    }
}
