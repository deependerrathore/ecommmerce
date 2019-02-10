<?php

namespace App\Controllers;
use Core\Controller;
use App\Models\Users;
use Core\H;
class HomeController extends Controller{
    
    public function indexAction(){

        //H::dnd(Users::currentUser());
        $this->view->render('home/index');
    }

}