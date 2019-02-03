<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Products;
use Core\H;
use App\Models\ProductImages;

class AdminproductsController extends Controller{
    public function __construct($controller, $action){
        parent::__construct($controller,$action);
        $this->view->setLayout('admin');
    }

    public function indexAction(){
        $this->view->render('admin/products/index');
    }

    public function addAction(){
        $product = new Products();
        $productImages = new ProductImages();
        if($this->request->isPost()){
            $files = $_FILES['productImages'];
            
            $this->request->csrfCheck();
            $imagesErrors = $productImages->validateImages($files);

            if (is_array($imagesErrors)) {
                $msg = "";
                foreach($imagesErrors as $name => $message){
                    $msg .= $message . "<br>";
                }
                
                $product->addErrorMessage('productImages[]',trim($msg));
            }
            
            $product->assign($this->request->get(),Products::blackList); 
            $product->save();

            if ($product->validationPassed()) {
                //upload images
                $structedFiles = ProductImages::restructureFiles($files);

                ProductImages::uploadProductIamges($product->id,$structedFiles);
            }
        }
        $this->view->product = $product;
        $this->view->displayErrors = $product->getErrorMessages();
        $this->view->postAction = PROJECT_ROOT . 'adminproducts/add';
        $this->view->render('admin/products/add');
    }
    
    
}