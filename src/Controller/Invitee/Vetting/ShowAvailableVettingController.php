<?php


namespace App\Controller\Invitee\Vetting;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/invitee/vetting", name="invitee_vetting_list", methods={"GET"})
 */
class ShowAvailableVettingController extends AbstractController
{
    public function __invoke()
    {
        return $this->render("invitee/vetting/list.html.twig", [
        ]);
    }
}
