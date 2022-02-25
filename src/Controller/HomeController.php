<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em, ArticleRepository $repo): Response
    {
        $lastArticles = $repo->findLastArticles();

        return $this->render('home/index.html.twig', [
            'lastArticles' => $lastArticles,
        ]);
    }
}
