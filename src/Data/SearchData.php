<?php 

namespace App\Data;
use App\Entity\SousCategories;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;

// Pour la recherche
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var Categories[]
     */
    public $categorie = [];

    /**
     * @var SousCategories[]
     */
    public $sousCategories = [];

    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var boolean
     */
    public $promotion = false;
}