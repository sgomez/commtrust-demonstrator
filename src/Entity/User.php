<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, TwoFactorInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @var array
     * @ORM\Column(type="json", nullable=true)
     */
    private $attributes;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $authCode;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\VirtualTicket", mappedBy="owner", cascade={"persist", "remove"})
     */
    private $virtualTicket;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VettingRequest", mappedBy="user", orphanRemoval=true)
     */
    private $vettingRequests;

    public function __construct()
    {
        $this->vettingRequests = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getEmail();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return User
     */
    public function setAttributes(array $attributes): User
    {
        $this->attributes = $attributes;
        return $this;
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isEmailAuthEnabled(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getEmailAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @inheritDoc
     */
    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    public function getVirtualTicket(): ?VirtualTicket
    {
        return $this->virtualTicket;
    }

    public function setVirtualTicket(VirtualTicket $virtualTicket): self
    {
        $this->virtualTicket = $virtualTicket;

        // set the owning side of the relation if necessary
        if ($virtualTicket->getOwner() !== $this) {
            $virtualTicket->setOwner($this);
        }

        return $this;
    }

    /**
     * @return Collection|VettingRequest[]
     */
    public function getVettingRequests(): Collection
    {
        return $this->vettingRequests;
    }

    public function addVettingRequest(VettingRequest $vettingRequest): self
    {
        if (!$this->vettingRequests->contains($vettingRequest)) {
            $this->vettingRequests[] = $vettingRequest;
            $vettingRequest->setUser($this);
        }

        return $this;
    }

    public function removeVettingRequest(VettingRequest $vettingRequest): self
    {
        if ($this->vettingRequests->contains($vettingRequest)) {
            $this->vettingRequests->removeElement($vettingRequest);
            // set the owning side to null (unless already changed)
            if ($vettingRequest->getUser() === $this) {
                $vettingRequest->setUser(null);
            }
        }

        return $this;
    }
}
