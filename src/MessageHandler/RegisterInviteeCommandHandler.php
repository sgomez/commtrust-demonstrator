<?php


namespace App\MessageHandler;


use App\Entity\Invitee;
use App\Message\RegisterInviteeCommand;
use App\Repository\InviteeRepository;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RegisterInviteeCommandHandler implements MessageHandlerInterface
{
    /**
     * @var InviteeRepository
     */
    private $inviteeRepository;
    /**
     * @var TotpAuthenticator
     */
    private $totpAuthenticator;

    public function __construct(InviteeRepository $inviteeRepository, GoogleAuthenticatorInterface $totpAuthenticator)
    {
        $this->inviteeRepository = $inviteeRepository;
        $this->totpAuthenticator = $totpAuthenticator;
    }

    public function __invoke(RegisterInviteeCommand $command)
    {
        $invitee = new Invitee();
        $invitee->setUuid($command->getUserId());
        $invitee->setTotpSecret($this->totpAuthenticator->generateSecret());

        $this->inviteeRepository->save($invitee);
    }
}
