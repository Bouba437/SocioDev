<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CommentController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/commentaire/{id}/edit", name="comment_edit")
     * @Security("is_granted('ROLE_USER')")
     */
    public function update(Comment $comment, Request $request): Response
    {
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->em->flush();

            $this->addFlash("success", "Votre commentaire a bien été mis à jour");

            return $this->redirect($_SERVER['HTTP_REFERER']);
        }

        return $this->render('comment/edit.html.twig', [
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
