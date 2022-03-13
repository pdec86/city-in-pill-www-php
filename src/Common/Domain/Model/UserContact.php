<?php

namespace App\Common\Domain\Model;

use App\Common\Domain\Model\VO\Email;
use App\Common\Domain\Model\VO\Phone;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_contact")
 */
class UserContact
{
    /**
     * @var User
     * @ORM\Id()
     * @ORM\OneToOne(targetEntity="App\Common\Domain\Model\User", fetch="LAZY", inversedBy="contact")
     * @ORM\JoinColumn(name="user_id")
     */
    private User $user;

    /**
     * @var string
     * @ORM\Column(name = "first_name")
     */
    private string $firstName;

    /**
     * @var string
     * @ORM\Column(name = "last_name")
     */
    private string $lastName;

    /**
     * @var Phone|null
     * @ORM\Embedded(class="App\Common\Domain\Model\VO\Phone", columnPrefix="contact_")
     */
    private ?Phone $phone;

    /**
     * @var Email|null
     * @ORM\Embedded(class="App\Common\Domain\Model\VO\Email", columnPrefix="contact_")
     */
    private ?Email $email;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param Phone|null $phone
     * @param Email|null $email
     */
    public function __construct(string $firstName, string $lastName, ?Phone $phone, ?Email $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return Phone|null
     */
    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    /**
     * @param Phone|null $phone
     */
    public function setPhone(?Phone $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return Email|null
     */
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * @param Email|null $email
     */
    public function setEmail(?Email $email): void
    {
        $this->email = $email;
    }
}
