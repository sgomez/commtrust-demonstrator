<?php


namespace App\Service;


use Surfnet\SamlBundle\SAML2\AuthnRequest;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class AcsContext
{
    const SESSION_PATH = 'geant/gateway/request';

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function setAuthnRequest(AuthnRequest $request)
    {
        $this->session->set(self::SESSION_PATH, serialize($request));
    }

    public function hasAuthnRequest(): bool
    {
        return $this->session->has(self::SESSION_PATH);
    }

    public function getAuthnRequest(): ?AuthnRequest
    {
        if (!$this->hasAuthnRequest()) {
            return null;
        }

        return unserialize($this->session->get(self::SESSION_PATH), [
            'allowed_class' => [AuthnRequest::class],
        ]);
    }

    public function clearAuthnRequest(): void
    {
        $this->session->remove(self::SESSION_PATH);
    }
}
