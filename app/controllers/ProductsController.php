<?php

namespace App\Controllers;
use Core\Controller;
use Core\H;

class ProductsController extends Controller{
    public function __construct($controller, $action){
        parent::__construct($controller,$action);
        $this->load_model('Products');
    }

    public function detailsAction($product_id){
        
        $product = $this->ProductsModel->findFirst([
            'conditions'=> ["id = ?"],
            'bind' => [(int)$product_id[0]]
        ]);
        $this->view->product = $product;
        $this->view->render('products/details');
    }
}