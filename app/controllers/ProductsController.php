<?php

namespace App\Controllers;
use Core\Controller;

class ProductsController extends Controller{
    public function __construct($controller, $action){
        parent::__construct($controller,$action);
        $this->load_model('Products');
    }

    public function detailsAction($product_id){
        $this->view->render('products/details');
    }
}