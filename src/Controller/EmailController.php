<?php


namespace App\Controller;


use App\Entity\Email;
use App\Entity\User;
use App\Form\MailType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="email/", name="email_")
 */
class EmailController extends AbstractController
{
    /**
     * @Route(path="send", name="send")
     */
    public function send(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager) {

        $userSender = $entityManager->getRepository(User::class)
            ->findOneBy(['username' => $this->getUser()->getUsername()]);

        $userRecipient = $entityManager->getRepository(User::class)
            ->find($request->get('user'));

        $email = new Email();

        $form = $this->createForm(MailType::class, $email);

        $form->handleRequest($request);

        $mailContent = $email->getContent();

        if ($form->isSubmitted() && $form->isValid()) {
            $sendEmail = (new TemplatedEmail())
                ->from(new Address($userSender->getEmail(), $userSender->getUsername()))
                ->to($userRecipient->getEmail())
                ->subject($email->getObject())
                ->text($mailContent.' *** This email was sent by sortir.com. Enjoy our website ! ***');
            ;

            $mailer->send($sendEmail);

            $this->addFlash('success', 'Your email has been successfully sent !');

            return $this->redirectToRoute('home_home');

        }

        return $this->render('email/send.html.twig', ['mailForm' => $form->createView(), 'userRecipient' => $userRecipient]);
    }

}