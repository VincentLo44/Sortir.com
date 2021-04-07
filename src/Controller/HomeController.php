<?php


namespace App\Controller;


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
     * @Route(path="", name="home",methods={"GET"})
     */
    public function home(){
        return $this->render("home/home.html.twig");
    }
}