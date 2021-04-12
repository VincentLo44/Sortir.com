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
    public function add(Request $request, EntityManagerInterface $entityManager) {
        $inscription = new Inscription();
        $dateInscription = new \DateTime('now');
        $outing = $entityManager->getRepository(Outing::class)->find($_POST['outing']);

        if($outing->getMaxNbInscriptions() <= $outing->getNbOfRegistrations()){
            $this->addFlash('danger', 'Too late !!! Outing is fully booked !');

        }
        if($dateInscription > $outing->getMaxDateInscription()){
            $this->addFlash('danger', 'Too late !!! Outing is finished !');

        }

        if($outing->getMaxNbInscriptions() > $outing->getNbOfRegistrations() && $dateInscription < $outing->getMaxDateInscription()){
            $inscription->setStatus('Registered');
            $inscription->setDate($dateInscription);
            $inscription->setUser($entityManager->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]));
            $inscription->setOuting($outing);
            $outing->setNbOfRegistrations($outing->getNbOfRegistrations()+1);
            $entityManager->persist($inscription);
            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Registration done !');
        }

        return $this->render('outing/detail.html.twig', ['outing' => $outing]);
    }

    /**
     * @Route(path="detail/{id}", requirements={"id" : "\d+"}, name="detail")
     */
    public function detail(Request $request, EntityManagerInterface $entityManager) {
        $id = $request->get('id');

        $outing = $entityManager->getRepository(Outing::class)->find($id);

        if (is_null($outing)) {
            return $this->render('error/outingNotFound.html.twig');
        }
        return $this->render('outing/detail.html.twig', ['outing' => $outing]);
    }
}