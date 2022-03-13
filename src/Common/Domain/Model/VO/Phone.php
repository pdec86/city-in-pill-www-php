<?php

namespace App\Common\Domain\Model\VO;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class Phone
{
    /**
     * @var string
     * @ORM\Column(name="phone", type="string")
     */
    private string $phone;

    /**
     * @param string $phone
     */
    public function __construct(string $phone)
    {
        if (!preg_match("/^[+]{0,1}[0-9 ]+$/", $phone)) {
            throw new \RuntimeException("Invalid phone number");
        }
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->phone;
    }
}
