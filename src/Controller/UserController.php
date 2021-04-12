<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->refresh($user);

            $this->addFlash('success', 'Congrats ! Your account of has been modified !');

            return $this->redirectToRoute('home_home');

        }

        $entityManager->refresh($user);

        return $this->render('user/myProfile.html.twig',
            ['sessionUser' => $user, 'userForm' => $form->createView(),
        ]);

    }

    /**
     * @Route(path="details_profile/{id}", requirements={"id" : "\d+"}, name="details_profile")
     */
    public function details(Request $request, EntityManagerInterface $entityManager) {

        $id = $request->get('id');

        $user = $entityManager->getRepository(User::class)->find($id);

        if (is_null($user)) {
            return $this->render('error/userNotFound.html.twig');
        }

        return $this->render('user/detailsProfile.html.twig', ['user' => $user]);


    }

}