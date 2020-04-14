<?php


namespace App\Controller\RegistrationAuthority;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ra", name="ra_admin")
 */
final class MainController extends AbstractController
{
    public function __invoke()
    {
        return $this->redirectToRoute('ra_locator_finder');
    }
}
