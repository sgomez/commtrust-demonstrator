<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfiguration;
use Scheb\TwoFactorBundle\Model\Totp\TotpConfigurationInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InviteeRepository")
 */
class Invitee implements UserInterface, TwoFactorInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $uuid;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isTotpAuthenticationEnabled = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isTotpAuthenticationValidated = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $totpSecret;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->uuid;
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
        // not needed for apps that do not check user passwords
    }

    /**
     * @return string
     */
    public function getTotpSecret(): string
    {
        return (string) $this->totpSecret;
    }

    /**
     * @param string $totpSecret
     *
     * @return Invitee
     */
    public function setTotpSecret(string $totpSecret): Invitee
    {
        $this->totpSecret = $totpSecret;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isGoogleAuthenticatorEnabled(): bool
    {
        return $this->isTotpAuthenticationEnabled;
    }

    /**
     * @inheritDoc
     */
    public function getGoogleAuthenticatorUsername(): string
    {
        return $this->getUsername();
    }

    /**
     * @inheritDoc
     */
    public function getGoogleAuthenticatorSecret(): ?string
    {
        return $this->totpSecret;
    }

    public function setGoogleAuthenticatorSecret(?string $googleAuthenticatorSecret): self
    {
        $this->totpSecret = $googleAuthenticatorSecret;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTotpAuthenticationValidated(): bool
    {
        return $this->isTotpAuthenticationValidated;
    }

    /**
     * @param bool $isTotpAuthenticationValidated
     * @return Invitee
     */
    public function setIsTotpAuthenticationValidated(bool $isTotpAuthenticationValidated): Invitee
    {
        $this->isTotpAuthenticationValidated = $isTotpAuthenticationValidated;

        return $this;
    }
}
