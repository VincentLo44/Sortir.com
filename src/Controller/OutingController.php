<?php


namespace App\Controller;


use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Inscription;
use App\Entity\Outing;
use App\Entity\OutingStatus;
use App\Entity\Place;
use App\Entity\User;
use App\Form\OutingCancellationType;
use App\Form\OutingType;
use App\Form\OutingTypeUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        $outing->setNbOfRegistrations(1);

        $form = $this->createForm(OutingType::class, $outing);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $outing->setPlanner($this->getDoctrine()->getRepository(User::class)
                ->findOneBy(['username' => $this->getUser()->getUsername()]));

            if(isset($_POST['campus'])) {
                $outing->setCampus($this->getDoctrine()->getRepository(Campus::class)
                    ->find($_POST['campus']));
            }

            $outing->setStatus($this->getDoctrine()->getRepository(OutingStatus::class)
                ->findOneBy(['description'=> 'Created']));

            if(isset($_POST['place'])) {
                $outing->setPlace($this->getDoctrine()->getRepository(Place::class)
                    ->findOneBy(['id' => $_POST['place']]));
            }

            $entityManager->persist($outing);

            $entityManager->flush();

            $inscription = new Inscription();
            $inscription->setStatus('Registered');
            $inscription->setDate(new \DateTime());
            $inscription->setUser($entityManager->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]));
            $inscription->setOuting($outing);

            $entityManager->persist($inscription);

            $entityManager->flush();

            $this->addFlash('success', 'Outing successfully added !');

            return $this->redirectToRoute('outing_detail',['id' => $outing->getId()]);
        }

        return $this->render('outing/add.html.twig', ['outingForm' => $form->createView(),
            'listCampus' => $this->getDoctrine()->getRepository(Campus::class)->findAll(),
            'listCities' => $this->getDoctrine()->getRepository(City::class)->findAll(),
            'listPlaces' => $this->getDoctrine()->getRepository(Place::class)->findAll()
        ]);
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

        if($outing->getStatus()->getDescription() == 'Closed'){

            $content = $this->renderView('error/outingClosed.html.twig', ['outing' => $outing]);

            return new Response($content, 404);
        }


        $user = $entityManager->getRepository(User::class)->findOneBy(['username'=>$this->getUser()->getUsername()]);
        $inscription = $entityManager->getRepository(Inscription::class)->findOneBy(['user' => $user, 'outing' => $outing]);
        $sub = false;

        if (!is_null($inscription) && $inscription->getStatus() == 'Registered') {
            $sub = true;
        }

        $listeInscriptions = $entityManager->getRepository(Inscription::class)->findBy(['outing' => $outing]);
        $inscriptions = [];

        foreach ($listeInscriptions as $inscrip) {
            if ($inscrip->getStatus() == 'Registered') {
                array_push($inscriptions, $inscrip);
            }
        }

        return $this->render('outing/detail.html.twig',
                                    ['outing' => $outing,
                                    'inscriptions' => $inscriptions,
                                        'sub' => $sub
                                    ]);
    }

    /**
     * @Route(path="update", name="update")
     */
    public function update(Request $request, EntityManagerInterface $entityManager) {

        $id = $request->get('outing');

        $outing = $entityManager->getRepository(Outing::class)->find($id);

        $form = $this->createForm(OutingTypeUpdate::class, $outing);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Congrats ! Your outing has been modified !');

            return $this->render('outing/detail.html.twig', ['outing' => $outing]);

        }

        return $this->render('outing/update.html.twig',
            ['outing' => $outing, 'outingForm' => $form->createView(),
                'listCampus' => $this->getDoctrine()->getRepository(Campus::class)->findAll(),
                'listCities' => $this->getDoctrine()->getRepository(City::class)->findAll(),
                'listPlaces' => $this->getDoctrine()->getRepository(Place::class)->findAll()
            ]);

    }

    /**
     * @Route(path="cancel/confirmation", name="cancel_confirmation")
     */
    public function cancelConfirmation(EntityManagerInterface $entityManager, Request $request)
    {

        $outing = $entityManager->getRepository(Outing::class)->find($request->get('outing'));

        $form = $this->createForm(OutingCancellationType::class, $outing);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $outing->setStatus($entityManager->getRepository(OutingStatus::class)->findOneBy(['description' => 'Cancelled']));

            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Congrats ! Your outing has been modified !');

            return $this->redirectToRoute('outing_detail', ['id' => $outing->getId()]);
        }
            return $this->render('outing/cancel.html.twig',
                ['outing' => $outing, 'outingCancellationForm' => $form->createView()]);


        }

    /**
     * @Route(path="myOutings", name="my_outings")
     */
    public function myOutings(Request $request, EntityManagerInterface $entityManager){

        $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $this->getUser()->getUsername()]);
        $outings = $entityManager->getRepository(Outing::class)->findBy(['planner' => $user]);
        $outingsRegistered = $entityManager->getRepository(Outing::class)->findOutingRegistered($user);

        return $this->render("outing/myOutings.html.twig",['outings' => $outings, 'outingsRegistered' => $outingsRegistered]);

        }

    /**
     * @Route(path="publish", name="publish")
     */
    public function publish(Request $request, EntityManagerInterface $entityManager) {

        $outing = $entityManager->getRepository(Outing::class)->find($request->get('outing'));

        $outing->setStatus($entityManager->getRepository(OutingStatus::class)->findOneBy(['description' => 'Opened']));

        $entityManager->flush();

        $this->addFlash('success', 'Congrats ! Your outing is published !');

        return $this->render('outing/detail.html.twig', ['outing' => $outing]);

        }

}