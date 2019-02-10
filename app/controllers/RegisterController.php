<?php

namespace App\Controllers;
use Core\Controller;
use Core\Router;
use Core\H;
use App\Models\Users;
use App\Models\Login;
class RegisterController extends Controller{
    
    public function onConstruct(){
        $this->view->setLayout('default');
    }
    
    /**
     * register the new user and redirects the user to login page
     *
     * @return void
     */
    public function registerAction(){
        
        $newUser = new Users();
        if($this->request->isPost()){
            $this->request->csrfCheck();

            $newUser->assign($this->request->get(),Users::blackListedFromKeys);

            $newUser->confirm = $this->request->get('confirm');
            if($newUser->save()){
                Router::redirect('register/login');
            }
            
        }
        $this->view->newUser = $newUser;
        $this->view->displayErrors = $newUser->getErrorMessages();
        $this->view->postAction = PROJECT_ROOT . 'register/register';
        $this->view->render('register/register');
    }
    
    /**
     * Log the user in and redirects to home page
     *
     * @return void
     */
    public function loginAction(){
        $loginModel = new Login();

        if($this->request->isPost()){
            $this->request->csrfCheck();
            $obj = $loginModel->assign($this->request->get());
            $loginModel->validator();
            if($loginModel->validationPassed()){
                //we are defining UsersModel in Controller
                $user = new Users();
                $user = $user->findByUsername($this->request->get('username'));
                
                if ($user && password_verify($this->request->get('password'), $user->password)) {
                    $remember = $loginModel->getRememberMeChecked();
                    $user->login($remember); //since $user is the object we can call method on it
                    Router::redirect('');
                } else {
                    $loginModel->addErrorMessage("username","There is something wrong with your username or password.");
                }
            }
            
            
        }
        $this->view->postAction = PROJECT_ROOT . 'register/login';
        $this->view->login = $loginModel;
        $this->view->displayErrors = $loginModel->getErrorMessages();
        $this->view->render('register/login');
    }

    /**
     * Logged out the user
     * @method logoutAction
     * @return void
     */
    public function logoutAction(){
        if (Users::currentUser()) {
            Users::currentUser()->logout();
        }
        Router::redirect('register/login');
    }
}