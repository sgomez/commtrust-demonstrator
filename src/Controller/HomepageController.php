<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/",
 *     name="homepage",
 *     methods={"GET"}
 * )
 */
class HomepageController extends AbstractController
{
    public function __invoke(): Response
    {
        if ($this->getUser() instanceof User) {
            return $this->redirectToRoute('invitee_profile');
        }

        return $this->render('homepage.html.twig');
    }
}
