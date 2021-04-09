<?php


namespace App\Controller;


use App\Entity\Campus;
use App\Entity\City;
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
 * @Route(path="outing/", name="outing_")
 */
class OutingController extends AbstractController
{

    /**
     * @Route(path="add", name="add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager) {
        $outing = new Outing();
        $outing->setStartingTime(new \DateTime());
        $outing->setMaxDateInscription(new \DateTime());
        $outing->setPlanner($this->getDoctrine()->getRepository(User::class)
            ->findOneBy(['username' => $this->getUser()->getUsername()]));
        $outing->setCampus($this->getDoctrine()->getRepository(Campus::class)
            ->findOneBy(['id' => $_POST['campus']]));
        $outing->setStatus($this->getDoctrine()->getRepository(OutingStatus::class)
            ->find(1));
        $outing->setPlace($this->getDoctrine()->getRepository(Place::class)
            ->findOneBy(['id' => $_POST['place']]));
        $form = $this->createForm(OutingType::class, $outing);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Outing successfully added !');

            return $this->redirectToRoute('home_home');
        }

        return $this->render('outing/add.html.twig', ['outingForm' => $form->createView(),
            'listCampus' => $this->getDoctrine()->getRepository(Campus::class)->findAll(),
            'listCities' => $this->getDoctrine()->getRepository(City::class)->findAll(),
            'listPlaces' => $this->getDoctrine()->getRepository(Place::class)->findAll()
        ]);
    }
}