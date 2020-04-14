<?php


namespace App\Security\Guard;


use App\Entity\User;
use App\Entity\VirtualTicket;
use App\Repository\UserRepository;
use App\Service\AcsContext;
use Psr\Log\LoggerInterface;
use SAML2\Assertion;
use SAML2\XML\saml\SubjectConfirmation;
use Surfnet\SamlBundle\Entity\HostedEntities;
use Surfnet\SamlBundle\Entity\IdentityProvider;
use Surfnet\SamlBundle\Http\PostBinding;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class SamlAuthenticator extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    /**
     * @var PostBinding
     */
    private $binding;
    /**
     * @var AcsContext
     */
    private $context;
    /**
     * @var HostedEntities
     */
    private $hostedEntities;
    /**
     * @var IdentityProvider
     */
    private $idp;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @inheritDoc
     */
    public function __construct(
        AcsContext $context,
        HostedEntities $hostedEntities,
        IdentityProvider $idp,
        LoggerInterface $logger,
        PostBinding $binding,
        RouterInterface $router,
        UserRepository $userRepository
    ) {
        $this->binding = $binding;
        $this->context = $context;
        $this->hostedEntities = $hostedEntities;
        $this->idp = $idp;
        $this->logger = $logger;
        $this->router = $router;
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate('login', [],
                Response::HTTP_TEMPORARY_REDIRECT
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request)
    {
        return 'saml_acs_respond' === $request->attributes->get('_route');
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request)
    {
        $response = $request->request->get('SAMLResponse');

        if (!$response) {
            throw new BadRequestHttpException(
                'No SAMLResponse parameter found in request to ACS respond endpoint'
            );
        }

        $this->logger->info(
            'Received HTTP request on ACS endpoint',
            [
                'SAMLResponse' => base64_decode($response),
            ]
        );

        if (!$this->context->hasAuthnRequest()) {
            $this->logger->error('Received assertion but no authn request found in context: session lost?');

            throw new BadRequestHttpException('Received an assertion but SSO was not initiated here');
        }

        try {
            $assertion = $this->binding->processResponse(
                $request,
                $this->idp,
                $this->hostedEntities->getServiceProvider()
            );
        } catch (\Exception $e) {
            $this->logger->error(
                'Error processing ACS request: ' . $e->getMessage(),
                [
                    'exception' => $e,
                ]
            );

            throw new BadRequestHttpException('Error processing ACS request');
        }

        $this->logger->info(
            'Processed ACS authn request',
            [
                'attributes' => $assertion->getAttributes(),
            ]
        );

        $this->logger->debug(
            'Full assertion in received authn response',
            [
                'assertion' => $assertion->toXML()->ownerDocument->saveXML(),
            ]
        );

        $inResponseTo = $this->getInResponseTo($assertion);
        $requestId = $this->context->getAuthnRequest()->getRequestId();
        if ($inResponseTo !== $requestId) {
            throw new BadRequestHttpException(
                "InResponseTo of asssertion {$inResponseTo} does not match request ID {$requestId}"
            );
        }

        // You should clear the authn request from your session state, and set the user as logged
        // in based on the attributes found
        $this->context->clearAuthnRequest();

        return $assertion->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['eduPersonTargetedID'][0];
        $mail = $credentials['mail'][0];
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($mail);

            $vticket = new VirtualTicket();
            $user->setVirtualTicket($vticket);
        }

        $user->setAttributes($credentials);
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $homepagePath = $this->router->generate('homepage');
        if (!$request->getSession() instanceof Session) {
            return new RedirectResponse($homepagePath);
        }

        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        if (!$targetPath) {
            return new RedirectResponse($homepagePath);
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * @inheritDoc
     */
    public function supportsRememberMe()
    {
        return false;
    }


    private function getInResponseTo(Assertion $assertion): ?string
    {
        /** @var SubjectConfirmation $subjectConfirmation */
        $subjectConfirmation = $assertion->getSubjectConfirmation()[0];

        return $subjectConfirmation->SubjectConfirmationData->InResponseTo;
    }
}
