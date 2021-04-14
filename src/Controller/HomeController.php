<?php


namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\Search;
use App\Entity\User;
use App\Form\HomeFiltersType;
use App\Repository\CampusRepository;
use App\Repository\OutingRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route(name="home_")
 */
class HomeController extends AbstractController
{

    /**
     * @Route(path="", name="home")
     * @return Response
     */
    public function home( outingRepository $outingRepository, Request $request): Response
    {
        $outings = [];
        $search = new Search();
        $form = $this->createForm(HomeFiltersType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (isset($_GET['campus']) && $_GET['campus']!= 0) {
                $search->setCampus($_GET['campus']);
            }
            if (isset($_GET['planner'])){
                $search->setPlanner($this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $_GET['planner']]));
            }

            if (isset($_GET['registered']) && $_GET['registered'] == 'true'){
                $search->setRegistered(true);

            }
            if (isset($_GET['registered']) && $_GET['registered'] == 'false'){
                $search->setNotRegistered(true);
            }

        }
        if(!is_null($this->getUser())) {
            $outings = $outingRepository->findAllVisibleQuery($search, $this->getUser());
        }
        dump($this->getUser());
        return $this->render('home/home.html.twig', [
            'outings' => $outings,
            'form' => $form->createView(),
            'listCampus' => $this->getDoctrine()->getRepository(Campus::class)->findAll(),

        ]);
    }




}