<?php


namespace App\Security\Guard;

use App\Entity\User;
use App\Message\RegisterInviteeCommand;
use App\Repository\InviteeRepository;
use App\Repository\UserRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GoogleAuthenticator extends SocialAuthenticator
{
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var InviteeRepository
     */
    private $inviteeRepository;
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    public function __construct(
        ClientRegistry $clientRegistry,
        InviteeRepository $inviteeRepository,
        MessageBusInterface $commandBus,
        RouterInterface $router
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->inviteeRepository = $inviteeRepository;
        $this->router = $router;
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return 'connect_google_check' === $request->attributes->get('_route');
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getClient());
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $userResource = $this
            ->getClient()
            ->fetchUserFromToken($credentials);
        $userResourceId = $userResource->getId() . '@google.com';

        $user = $this->inviteeRepository->findOneBy(['uuid' => $userResourceId]);

        if (!$user) {
            $this->commandBus->dispatch(
                new RegisterInviteeCommand($userResourceId)
            );

            $user = $this->inviteeRepository->findOneBy(['uuid' => $userResourceId]);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request, $providerKey);

        if (!$targetPath) {
            // Change it to your default target
            $targetPath = $this->router->generate('homepage');
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, ?AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate('connect_google_start'),
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    private function getClient(): OAuth2ClientInterface
    {
        return $this
            ->clientRegistry
            ->getClient('google');
    }

    /**
     * Returns the URL (if any) the user visited that forced them to login.
     */
    protected function getTargetPath(Request $request, string $providerKey): ?string
    {
        if (!$request->hasSession()) {
            return null;
        }

        return $request->getSession()->get('_security.' . $providerKey . '.target_path');
    }
}
