<?php


namespace App\Controller\Security;


use Surfnet\SamlBundle\Http\XMLResponse;
use Surfnet\SamlBundle\Metadata\MetadataFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/saml/acs/metadata",
 *     name="saml_acs_metadata",
 *     methods={"GET"}
 * )
 */
final class SamlConnectMetadataController
{
    public function __invoke(Request $request, MetadataFactory $metadataFactory): Response
    {
        return  new XMLResponse($metadataFactory->generate());
    }
}
