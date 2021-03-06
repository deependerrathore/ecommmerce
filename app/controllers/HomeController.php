<?php

namespace App\Controllers;
use Core\Controller;
use Core\DB;
use Core\FH;
use App\Models\Users;


class HomeController extends Controller{
    
    public function __construct($controller, $action){
        parent::__construct($controller,$action);
    }
    public function indexAction(){
        $this->view->render('home/index');
    }

    public function searchAction(){
        if (isset($_POST['search'])) {
            $toSearch = explode(" " ,FH::sanatize($_POST['searchbox']));

            if (count($toSearch) == 1) { //check if the count is 1 i.e. it should be username
                $toSearch = str_split($toSearch[0],2);//spliting the string in chunk of 2 chars i.e. deepender = de ep en de r                
            }

            $whereClause = "";
            for ($i=0; $i < count($toSearch); $i++) { 
                $whereClause .= " OR username LIKE ? ";
                $paramsArray[$i] = '%'.$toSearch[$i].'%';
            }
            $paramsArray = array_merge(array('%'.$toSearch[0].'%'), $paramsArray);

            //dnd($paramsArray);
            $users = DB::getInstance()->query("SELECT username FROM users WHERE username like ? $whereClause",$paramsArray);  

            echo '<pre>';
            print_r($users);
            echo '</pre>';
            $whereClause = "";
            for ($i=0; $i < count($toSearch); $i++) { 
                if($i/2){
                    $whereClause .= " OR postbody LIKE ? ";
                    $paramsArray[$i] = '%'.$toSearch[$i].'%';
                }
            }
            $paramsArray = array_merge(array('%'.$toSearch[0].'%'), $paramsArray);

            //dnd($paramsArray);
            $posts = DB::getInstance()->query("SELECT postbody FROM posts WHERE postbody like ? $whereClause",$paramsArray);  

            echo '<pre>';

            print_r($posts);
            echo '</pre>';            
        }
    }

    
}