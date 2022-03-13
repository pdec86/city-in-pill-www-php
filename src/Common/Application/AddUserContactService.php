<?php

namespace App\Common\Application;

use App\Common\Domain\Model\UserContact;
use App\Common\Domain\Model\VO\Email;
use App\Common\Domain\Model\VO\Phone;
use App\Common\Infrastructure\Repository\UserRepository;

class AddUserContactService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $username, string $firstName, string $lastName, ?string $phone, ?string $email): void
    {
        $user = $this->userRepository->loadUserByIdentifier($username);
        $user->setContact(new UserContact($firstName, $lastName, $phone !== null ? new Phone($phone) : null,
            $email !== null ? new Email($email) : null));
        $user->getContact()->setUser($user);
        $this->userRepository->save($user);
    }
}
