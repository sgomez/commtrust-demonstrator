<?php


namespace App\Controller\Invitee\Token;


use App\Entity\Invitee;
use Endroid\QrCode\Factory\QrCodeFactory;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/invitee/token/totp", name="invite_token_totp", methods={"GET", "POST"})
 * @Security("is_granted('ROLE_USER')")
 */
class RegisterTOTPTokenController extends AbstractController
{
    /**
     * @var GoogleAuthenticatorInterface
     */
    private $totpAuthenticator;
    /**
     * @var QrCodeFactory
     */
    private $qrCodeFactory;

    public function __construct(GoogleAuthenticatorInterface $totpAuthenticator, QrCodeFactory $qrCodeFactory)
    {
        $this->totpAuthenticator = $totpAuthenticator;
        $this->qrCodeFactory = $qrCodeFactory;
    }

    public function __invoke(Request $request)
    {
        /** @var Invitee $user */
        $user = $this->getUser();

        $qrCodeContent = $this->totpAuthenticator->getQRContent($user);
        $qrCode = $this->qrCodeFactory->create($qrCodeContent);

        $defaultData = ['code' => null];
        $form = $this->createFormBuilder($defaultData)
            ->add('code', TextType::class, ['required' => true])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $isValidCode = $this->totpAuthenticator->checkCode($user, $data['code']);
            $user->setIsTotpAuthenticationValidated(true);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render("invitee/token/totp.html.twig", [
            'qr' => $qrCodeContent,
            'form' => $form->createView()
        ]);
    }
}
