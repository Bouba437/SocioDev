<?php

namespace App\Service;

use App\Entity\Category;

class Search {
    
    /**
     * Le mot ou l'expression à rechercher
     *
     * @var string
     */
    public $string = "";

    /**
     * Catégorie à rechercher
     *
     * @var Category()
     */
    public $categories = [];
}