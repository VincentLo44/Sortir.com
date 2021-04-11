<?php


namespace App\Controller;


use App\Entity\Outing;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route(path="user/", name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route(path="my_profile", name="my_profile")
     */
    public function update(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder) {
        $user = $entityManager->getRepository(User::class)
            ->findOneBy(['username' => $this->getUser()->getUsername()]);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

//        if ($_POST['user[password][first]'] != $_POST['user[password][second]']){
//            $this->addFlash('danger', 'Passwords ar note the same !');
//
//            return $this->redirectToRoute('user_my_profile');
//        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Congrats ! Your account of has been modified !');

            return $this->redirectToRoute('home_home');
        }

        return $this->render('user/myProfile.html.twig',
            ['sessionUser' => $user, 'userForm' => $form->createView(),
        ]);

            // TODO => gérer le flash message en cas d'erreur
//        $this->addFlash('warning', 'There is a problem !');

    }

    /**
     * @Route(path="details_profile/{id}", requirements={"id" : "\d+"}, name="details_profile")
     */
    public function details(Request $request, EntityManagerInterface $entityManager) {

        $id = $request->get('id');

        $outing = $entityManager->getRepository(Outing::class)->find($id);

        if (is_null($outing)) {
            return $this->render('error/userNotFound.html.twig');
        }

        return $this->render('user/detailsProfile.html.twig', ['outing' => $outing]);


    }

}