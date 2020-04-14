<?php


namespace App\Controller\Invitee\Security;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/connect/google/check", name="connect_google_check")
 */
class GoogleConnectCheckController extends AbstractController
{
    public function __invoke(): void
    {
        throw new RuntimeException('This method should not be called.');
    }
}
