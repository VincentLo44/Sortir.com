<?php


namespace App\Controller;


use App\Entity\Outing;
use App\Entity\Serie;
use App\Repository\OutingRepository;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route(name="home_")
 */
class HomeController extends AbstractController
{

    /**
     * @Route(path="", name="home", requirements={"numPage":"\d+"}, methods={"GET"})
     */
    public function home( OutingRepository $outingRepository): \Symfony\Component\HttpFoundation\Response
    {

        $outings = $outingRepository->findAll();


        return $this->render('home/home.html.twig', [
            'outings'=>$outings
        ]);
    }




}