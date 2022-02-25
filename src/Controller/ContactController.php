<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\Mailjet;
use App\Notification\MailContact;
use App\Notification\ContactNotification;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {

        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to("boubadevri@gmail.com")
                ->subject($contact->get('object')->getData())
                ->htmlTemplate('contact/email.html.twig')
                ->context([
                    'mail' => $contact->get('email')->getData(),
                    'message' => $contact->get('message')->getData(),
                    'name' => $contact->get('firstname')->getData() . " " . $contact->get('lastname')->getData()
                ]);
            $mailer->send($email);
            
            $this->addFlash("success", "Votre message a bien été envoyé");
            
            return $this->redirectToRoute('contact');
            
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
