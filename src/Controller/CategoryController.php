<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="category_index")
     */
    public function index(CategoryRepository $repo): Response
    {
        $categories = $repo->findAll();

        return $this->render('category/index.html.twig', [
            // 'categories' => $categories,
        ]);
    }

    /**
     * Permet d'afficher les articles appartenant à une catégorie
     * 
     * @Route("categorie/{slug}", name="category_show")
     *
     * @param Category $category
     * @return void
     */
    public function show(Category $category) {

        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }
}
