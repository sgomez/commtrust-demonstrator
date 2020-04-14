<?php


namespace App\Controller\Invitee\Token;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/invitee/token", name="invitee_token_list", methods={"GET"})
 */
class ShowAvailableTokenController extends AbstractController
{
    public function __invoke()
    {
        return $this->render("invitee/token/list.html.twig", [
            
        ]);
    }
}
