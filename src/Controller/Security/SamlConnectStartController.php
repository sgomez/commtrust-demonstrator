<?php


namespace App\Controller\Security;

use App\Service\AcsContext;
use Psr\Log\LoggerInterface;
use Surfnet\SamlBundle\Entity\HostedEntities;
use Surfnet\SamlBundle\Entity\IdentityProvider;
use Surfnet\SamlBundle\SAML2\AuthnRequestFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route(
 *     "/saml/acs/init",
 *     name="saml_acs_init",
 *     methods={"GET"},
 *     requirements={
 *         "_format": "xml",
 *     },
 * )
 */
class SamlConnectStartController
{
    public function __invoke(
        Request $httpRequest,
        HostedEntities $hostedEntities,
        IdentityProvider $idp,
        AcsContext $context,
        LoggerInterface $logger
    ): Response {
        $request = AuthnRequestFactory::createNewRequest(
            $hostedEntities->getServiceProvider(),
            $idp
        );

        $logger->info(
            sprintf(
                'Starting SSO request with ID %s to IDP %s',
                $request->getRequestId(),
                $idp->getEntityId()
            ),
            ['request' => $request->getUnsignedXML()]
        );

        // Store the request so we can validate the response on acs respond.
        $context->setAuthnRequest($request);

        // That's it, we're good to go!
        return new RedirectResponse(
            sprintf(
                '%s?%s',
                $idp->getSsoUrl(),
                $request->buildRequestQuery()
            )
        );
    }
}
