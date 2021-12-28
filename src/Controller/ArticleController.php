<?php

namespace App\Controller;

use App\Service\Search;
use App\Entity\Article;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
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
    public function show(Article $article) {

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
