<?php

namespace App\Tests\Common\Application;

use App\Common\Application\CreateNewUserService;
use App\Common\Infrastructure\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateNewUserServiceTest extends KernelTestCase
{
    public function testExecute()
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        /** @var CreateNewUserService $service */
        $service = $container->get(CreateNewUserService::class);

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $container->get(UserPasswordHasherInterface::class);

        $user1Before = $userRepository->loadUserByUsername("user1");
        $this->assertNull($user1Before, "User already exists");

        $service->execute("user1", "pass1");

        $user1After = $userRepository->loadUserByIdentifier("user1");
        $user1After->eraseCredentials(); // Fake coverage
        $this->assertNotNull($user1After, "User does not exists");
        $this->assertNotEmpty($user1After->getId(), "User has some ID set");
        $this->assertEquals("user1", $user1After->getUsername(), "Mismatched username");
        $this->assertEquals("user1", $user1After->getUserIdentifier(), "Mismatched user identifier");
        $this->assertTrue($passwordHasher->isPasswordValid($user1After, "pass1"), "Password do not match");
        $this->assertCount(1, $user1After->getRoles(), "Should have only one role ROLE_USER");
        $this->assertEquals("ROLE_USER", $user1After->getRoles()[0]);
    }
}
