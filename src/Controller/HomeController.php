<?php


namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Outing;
use App\Entity\Search;
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
        $search = new Search();
        $form = $this->createForm(HomeFiltersType::class, $search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search->setName($_GET['name']);

        }
        $outings = $outingRepository->findAllVisibleQuery($search);

        return $this->render('home/home.html.twig', [
            'outings'=>$outings,
            'form'=>$form->createView()
        ]);
    }




}