<?php


namespace App\Controller\Invitee\Security;


use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @Route("/connect/google", name="connect_google_start")
 */
class GoogleConnectStartController extends AbstractController
{
    use TargetPathTrait;

    public function __invoke(Request $request, ClientRegistry $clientRegistry): Response
    {
        $this->storeTargetPath($request);

        return $clientRegistry
            ->getClient('google')
            ->redirect([
                'openid', 'email', 'profile',
            ], [])
            ;
    }

    private function storeTargetPath(Request $request): void
    {
        $targetPath = $request->query->get('_target_path');
        if ($targetPath && $request->hasSession() && ($session = $request->getSession()) instanceof SessionInterface) {
            $this->saveTargetPath($session, 'main', $targetPath);
        }
    }
}
