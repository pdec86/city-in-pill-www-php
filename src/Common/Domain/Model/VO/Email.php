<?php

namespace App\Common\Domain\Model\VO;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Email
{
    /**
     * @var string
     * @ORM\Column(name="email", type="string")
     */
    private string $email;

    /**
     * @param string $email
     */
    public function __construct(string $email)
    {
        if (!preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i", $email)) {
            throw new \RuntimeException("Invalid e-mail address");
        }
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->email;
    }
}