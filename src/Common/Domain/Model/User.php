<?php

namespace App\Common\Domain\Model;

use App\Common\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="city_user")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string Username as identifier / login
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $username;

    /**
     * @var string|null The hashed password
     * @ORM\Column(type="string")
     */
    private ?string $password;

    /**
     * @var array User roles
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var UserContact|null User contact details
     * @ORM\OneToOne(targetEntity="App\Common\Domain\Model\UserContact", fetch="EAGER", cascade={"all"}, mappedBy="user")
     */
    private ?UserContact $contact = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRole(string $role) {
        $this->roles[] = $role;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return UserContact|null
     */
    public function getContact(): ?UserContact
    {
        return $this->contact;
    }

    /**
     * @param UserContact $contact
     */
    public function setContact(UserContact $contact): void
    {
        $this->contact = $contact;
    }

    public function getSalt(): ?string
    {
        return null;
//        return 'zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa';
    }

    public function eraseCredentials()
    {
        // commented because of some problems with logging in (detecting User change)
//        $this->password = '';
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}