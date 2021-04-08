<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * @Route(path="user/", name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route(path="my_profile", name="my_profile")
     */
    public function update(Request $request, EntityManagerInterface $entityManager) {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->findOneBy(['username' => $this->getUser()->getUsername()]);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Congrats ! Your account of has been modified !');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->redirectToRoute('home_home');
        }

        return $this->render('user/myProfile.html.twig',
            ['sessionUser' => $user, 'userForm' => $form->createView(),
        ]);

            // TODO => gérer le flash message en cas d'erreur
//        $this->addFlash('warning', 'There is a problem !');

    }

}