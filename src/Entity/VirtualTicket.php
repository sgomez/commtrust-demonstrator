<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VirtualTicketRepository")
 */
class VirtualTicket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $locator;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVouched;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="virtualTicket", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isVouched = false;
        $this->locator = self::createLocator();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocator(): ?string
    {
        return $this->locator;
    }

    public function setLocator(string $locator): self
    {
        $this->locator = $locator;

        return $this;
    }

    public function getIsVouched(): ?bool
    {
        return $this->isVouched;
    }

    public function setIsVouched(bool $isVouched): self
    {
        $this->isVouched = $isVouched;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    private static function createLocator(): string
    {
        $characters = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
        $locator = '';
        for ($i = 0; $i < 8; $i++) {
            $locator .= $characters[random_int(1, strlen($characters)) - 1];
        }

        return $locator;
    }
}
