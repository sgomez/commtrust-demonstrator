<?php


namespace App\Controller\Security;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/saml/acs/sp",
 *     name="saml_acs_respond",
 *     methods={"POST"},
 *     requirements={
 *         "_format": "xml",
 *     },
 * )
 */
class SamlConnectCheckController
{
    public function __invoke()
    {
        throw new \RuntimeException('This method should not be called.');
    }
}
