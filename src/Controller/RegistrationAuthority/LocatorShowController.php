<?php


namespace App\Controller\RegistrationAuthority;


use App\Entity\VirtualTicket;
use App\Repository\VirtualTicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/ra/locator/{locator}",
 *     name="ra_locator_show",
 *     methods={"GET"}
 * )
 */
final class LocatorShowController extends AbstractController
{
    /**
     * @var VirtualTicketRepository
     */
    private $virtualTicketRepository;

    public function __construct()
    {
    }

    public function __invoke(VirtualTicket $virtualTicket)
    {
        return $this->render('ra/locator_show.html.twig', [
            'vticket' => $virtualTicket,
        ]);
    }
}
