<?php


namespace App\Controller\RegistrationAuthority;


use App\Form\VirtualTicketLocatorType;
use App\Repository\VirtualTicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/ra/locator",
 *     name="ra_locator_finder",
 *     methods={"GET", "POST"}
 * )
 */
class LocatorFinderController extends AbstractController
{
    /**
     * @var VirtualTicketRepository
     */
    private $virtualTicketRepository;

    public function __construct(VirtualTicketRepository $virtualTicketRepository)
    {
        $this->virtualTicketRepository = $virtualTicketRepository;
    }

    public function __invoke(Request $request)
    {
        $form = $this->createForm(VirtualTicketLocatorType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locator = $form->getData()->getLocator();
            $vTicket = $this->virtualTicketRepository->findOneBy(['locator' => $locator]);

            if (!$vTicket) {
                $form->get('locator')->addError(new FormError('Locator not found'));

                return $this->render('ra/locator_finder.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            return $this->redirectToRoute('ra_locator_show', ['locator' => $locator]);
        }

        return $this->render('ra/locator_finder.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
