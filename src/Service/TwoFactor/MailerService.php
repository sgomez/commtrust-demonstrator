<?php


namespace App\Service\TwoFactor;


use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService implements AuthCodeMailerInterface
{
    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @inheritDoc
     */
    public function sendAuthCode(TwoFactorInterface $user): void
    {
        $email = (new Email())
            ->from('noreply@uco.es')
            ->to($user->getEmailAuthRecipient())
            ->subject('Authentication Code')
            ->text($user->getEmailAuthCode())
            ->html('<h1>'.$user->getEmailAuthCode().'</h1>');

        $this->mailer->send($email);
    }
}
