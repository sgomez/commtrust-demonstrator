<?php


namespace App\Controller\Invitee;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Route(
 *     "/invitee/profile",
 *     name="invitee_profile",
 *     methods={"GET"}
 * )
 */
class ProfileController extends AbstractController
{
    public function __invoke()
    {
        /** @var User $user */
        $user = $this->getUser();
        $id = $user->getVirtualTicket()->getLocator();

        dump($user->getAttributes());

        return $this->render('invitee/profile.html.twig', [
            'code' => $this->generateUrl('invitee_ticket', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }
}
