<?php

namespace App\Controller;

use App\Service\Search;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/articles", name="articles_index")
     */
    public function index(ArticleRepository $repo, Request $request): Response
    {
        $search = new Search();

        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $articles = $repo->findWithSearch($search);
        } else {
            $articles = $repo->findAll();
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'afficher un article
     * 
     * @Route("article/{slug}", name="article_show")
     *
     * @param Article $article
     * @return Response
     */
    public function show(Article $article, Request $request) {
        // Gestion des commentaires
        $comment = new Comment();

        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $author = $this->getUser();

            $comment->setAuthor($author)
                    ->setArticle($article);

            // On récupère le contenu du champ parentid
            $parentid = $commentForm->get('parentid')->getData();
            // On cherche le commentaire correspondant
            if($parentid != null) {
                $parent = $this->em->getRepository(Comment::class)->find($parentid);
            }
            // On définit le parent
            $comment->setParent($parent ?? null);

            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash("success", "Votre commentaire a été ajouté avec succès.");

            return $this->redirectToRoute('article_show', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
