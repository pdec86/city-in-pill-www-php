<?php

namespace App\Common\Application;

use App\Common\Application\Exception\UserNotFoundException;
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

    /**
     * @throws UserNotFoundException
     */
    public function execute(string $username, string $firstName, string $lastName, ?string $phone, ?string $email): void
    {
        $user = $this->userRepository->loadUserByIdentifier($username);
        if ($user == null) {
            throw new UserNotFoundException();
        }

        if ($user->getContact() === null) {
            $user->setContact(new UserContact($firstName, $lastName, $phone !== null ? new Phone($phone) : null,
                $email !== null ? new Email($email) : null));
            $user->getContact()->setUser($user);
        } else {
            $user->getContact()->setFirstName($firstName);
            $user->getContact()->setLastName($firstName);
            $user->getContact()->setPhone($phone !== null ? new Phone($phone) : null);
            $user->getContact()->setEmail($email !== null ? new Email($email) : null);
        }

        $this->userRepository->save($user);
    }
}
