<?php

declare(strict_types=1);

namespace App\Controller\Security;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/logout", name="admin_logout")
 */
class LogoutController
{
    public function __invoke(): void
    {
        throw new \RuntimeException('This method should not be called.');
    }
}
