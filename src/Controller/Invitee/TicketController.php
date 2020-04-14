<?php


namespace App\Controller\Invitee;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/invitee/ticket/{id}",
 *     name="invitee_ticket",
 *     methods={"GET"}
 * )
 */
class TicketController extends AbstractController
{
    public function __invoke(string $id)
    {
        /** @var User $user */
        $user = $this->getUser();
        $attributes = $user->getAttributes();

        dump($attributes);

        return $this->render('invitee/ticket.html.twig', [
            'id' => $id,
            'attributes' => $attributes,
        ]);
    }
}
