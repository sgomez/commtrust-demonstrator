<?php


namespace App\Controller\RegistrationAuthority;


use App\Entity\VirtualTicket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/ra/locator/{locator}",
 *     name="ra_locator_validate",
 *     methods={"POST"}
 * )
 */
class LocatorValidateController extends AbstractController
{
    public function __invoke(Request $request, VirtualTicket $virtualTicket)
    {
        if ($this->isCsrfTokenValid('validate'.$virtualTicket->getId(), $request->request->get('_token'))) {
            $virtualTicket->setIsVouched(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return $this->redirectToRoute('ra_locator_show', ['locator' => $virtualTicket->getLocator()]);
    }
}
