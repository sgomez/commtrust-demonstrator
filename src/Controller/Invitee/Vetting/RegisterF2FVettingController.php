<?php


namespace App\Controller\Invitee\Vetting;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/invitee/vetting/f2f", name="invitee_vetting_f2f_register", methods={"GET", "POST"})
 */
class RegisterF2FVettingController extends AbstractController
{
    public function __invoke(Request $request)
    {

    }
}
