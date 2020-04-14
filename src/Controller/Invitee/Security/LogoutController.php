<?php


namespace App\Controller\Invitee\Security;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logout", name="logout")
 */
class LogoutController
{
    public function __invoke(): void
    {
        throw new \RuntimeException('This method should not be called.');
    }
}
