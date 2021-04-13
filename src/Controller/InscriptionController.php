<?php


namespace App\Controller;


use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Inscription;
use App\Entity\Outing;
use App\Entity\OutingStatus;
use App\Entity\Place;
use App\Entity\User;
use App\Form\OutingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="Inscription/", name="inscription_")
 */
class InscriptionController extends AbstractController
{

    /**
     * @Route(path="add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager)
    {

        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['username' => $this->getUser()->getUsername()]);
        $dateInscription = new \DateTime('now');
        $outing = $entityManager->getRepository(Outing::class)->find($_POST['outing']);
        $inscription = $entityManager->getRepository(Inscription::class)
            ->findOneBy([
                'user' => $user,
                'outing' => $outing
            ]);

        if ((!is_null($inscription) && !empty($inscription)) && ($inscription->getStatus()=="Registered" || $inscription->getStatus()=="Archived")) {
            $this->addFlash('danger', 'You are already registered on this outing !');
        } else {

            if ($outing->getMaxNbInscriptions() <= $outing->getNbOfRegistrations()) {
                $this->addFlash('danger', 'Too late !!! Outing is fully booked !');
            }
            if ($dateInscription > $outing->getMaxDateInscription()) {
                $this->addFlash('danger', 'Too late !!! Outing is finished !');

            }

            if ($outing->getMaxNbInscriptions() > $outing->getNbOfRegistrations() && $dateInscription < $outing->getMaxDateInscription()) {
                $inscription->setStatus('Registered');
                $inscription->setDate($dateInscription);
                $inscription->setUser($entityManager->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]));
                $inscription->setOuting($outing);
                $outing->setNbOfRegistrations($outing->getNbOfRegistrations() + 1);
                $entityManager->persist($inscription);
                $entityManager->persist($outing);
                $entityManager->flush();

                $this->addFlash('success', 'Registration done !');
            }
        }

        return $this->render('outing/detail.html.twig', ['outing' => $outing]);
    }


    /**
     * @Route(path="unsubscribe", name="unsubscribe")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return RedirectResponse
     */
    public function unsubToOuting(EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {

        $outing = $entityManager->getRepository(Outing::class)->findOneBy(['id' => $request->get('outing')]);
        $user = $entityManager->getRepository(User::class)->findOneBy(['username'=>$this->getUser()->getUsername()]);
        $inscription = $entityManager->getRepository(Inscription::class)->findOneBy(['user' => $user, 'outing' => $outing]);

        $inscription->setStatus('Cancelled');
        $outing->setNbOfRegistrations($outing->getNbOfRegistrations() - 1);

        $entityManager->persist($inscription);
        $entityManager->persist($outing);
        $entityManager->flush();
        $this->addFlash('success', 'You are no longer registered in this outing');

        return $this->redirectToRoute('outing_detail');
    }


}