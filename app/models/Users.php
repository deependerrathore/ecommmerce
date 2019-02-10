<?php

namespace App\Models;
use Core\Model;

use App\Models\Users as Users;
use App\Models\UserSessions;
use Core\Session;
use Core\Cookie;
use Core\FH;
use Core\H;
use Core\Validators\MinValidator;
use Core\Validators\MaxValidator;
use Core\Validators\RequiredValidator;
use Core\Validators\EmailValidator;
use Core\Validators\UniqueValidator;
use Core\Validators\MatchesValidator;
use Core\Validators\UsernameValidator;

class Users extends Model{
    protected static $_table='users', $_softDelete = true;
    public static $currentLoggedInUser = null;

    private $_isLoggedIn, $_sessionName , $_cookieName;

    public $id, $username,$password,$email,$fname,$lname,$acl,$deleted=0,$whenaccountcreated ,$verified = 0,$profileimg,$confirm;

    const blackListedFromKeys = ['id','deleted'];

    public function __construct(){
        $this->_sessionName = CURRENT_USER_SESSION_NAME;
        $this->_cookieName = REMEMBER_ME_COOKIE_NAME;
    }

    public function validator(){
        $this->runValidation(new RequiredValidator($this,['field'=>'fname','msg' => 'First name is required.']));
        $this->runValidation(new RequiredValidator($this,['field'=>'lname','msg' => 'Last name is required.']));
        $this->runValidation(new RequiredValidator($this,['field'=>'email','msg' => 'Email is required.']));
        $this->runValidation(new EmailValidator($this,['field'=>'email','msg' => 'Your must provide a valid email address.']));
        $this->runValidation(new MaxValidator($this,['field'=>'email','rule'=>150,'msg'=>'Email should be not more than 150 characters.']));
        $this->runValidation(new UniqueValidator($this,['field'=>'email','msg'=>'Email in use. Please choose another email.']));

        $this->runValidation(new MinValidator($this,['field'=>'username','rule'=>6,'msg'=>'Username should be at least 6 characters.']));
        $this->runValidation(new MaxValidator($this,['field'=>'username','rule'=>150,'msg'=>'Username should be not more than 150 characters.']));
        $this->runValidation(new UniqueValidator($this,['field'=>['username'],'msg'=>'Username already exist. Please choose another username.']));
        $this->runValidation(new UsernameValidator($this,['field'=>'username','msg'=>'Username must be valid. only _ is acceptable as special character.']));

        $this->runValidation(new RequiredValidator($this,['field'=>'password','msg' => 'Password is required.']));
        if ($this->isNew()) {
            $this->runValidation(new MatchesValidator($this,['field'=>'password','rule'=>$this->confirm,'msg'=>'Password and confirm password do not match.']));
        }
        $this->runValidation(new MinValidator($this,['field'=>'password','rule'=>6,'msg'=>'Password should be at least 6 characters.']));


    }

    public function beforeSave(){
        if ($this->isNew()) {
            $this->fname = ucfirst($this->fname);
            $this->lname = ucfirst($this->lname);
            $this->password = password_hash($this->password,PASSWORD_DEFAULT);
            $this->whenaccountcreated = date('Y-m-d H:i:s');
            $this->profileimg = 'default';
        }
        
    }

    public static function findByUsername($username){
        return self::findFirst([
            'conditions' => 'username = ?',
            'bind' => [$username]
        ]);
    }

    public static function findByEmail($email){
        return self::findFirst([
            'conditions' => 'email = ?',
            'bind' => [$email]
        ]);
    }

    public static function currentUser(){
        if(!isset(self::$currentLoggedInUser) && Session::exists(CURRENT_USER_SESSION_NAME)){

            $u = new Users((int)Session::get(CURRENT_USER_SESSION_NAME));

            self::$currentLoggedInUser = $u;
        }
        return self::$currentLoggedInUser;
    }

    public function login($rememberMe = false){
        //set the session with the ID
        Session::set($this->_sessionName,$this->id);
        
        if ($rememberMe) {
            //generate a unique hash that we will be using in session and cookie
            $cstrong = TRUE;
            $token =  bin2hex(openssl_random_pseudo_bytes(64,$cstrong));
            //getting the useragent from Session class
            $user_agent = Session::uagent_no_version();


            //set the cookie with the hash value
            Cookie::set($this->_cookieName,$token,REMEMBER_ME_COOKIE_EXPIRY);
            $fields = [
                'token' => sha1($token),
                'user_agent' =>$user_agent,
                'user_id' => $this->id
            ];
            self::$_db->query("DELETE FROM user_sessions WHERE user_id = ? AND user_agent = ?", [$this->id,$user_agent]);

            self::$_db->insert('user_sessions',$fields);
        }   
    }

    public static function loginUserFromCookie(){
        $userSession = UserSessions::getFromCookie();
        
        if($userSession && $userSession->user_id != ''){
            $user= new Self((int)$userSession->user_id);

            if($user){
                $user->login();
            }
            return $user;
        }

        return;
        
    }

    /**
     * To logout of the current session
     *
     * @param boolean $allDevices pass TRUE to logout of the all devices
     * @return boolean
     */
    public function logout(){
        
        $userSession = UserSessions::getFromCookie();
        if ($userSession) $userSession->delete();
      
        Session::delete(CURRENT_USER_SESSION_NAME);
        if (Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
            Cookie::delete(REMEMBER_ME_COOKIE_NAME, REMEMBER_ME_COOKIE_EXPIRY);
        }
        self::$currentLoggedInUser = null;
        return true;
        
    }

    public function acls(){
        if(empty($this->acl)) return [];
        return json_decode($this->acl,true);
    }
    
    

    public static function addAcl($user_id,$acl){

        $user = self::findById($user_id);
        if(!$user) return false;
        $acls = $user->acls();
        if (!in_array($acl,$acls)) {
            $acls[] =$acl;
            $user->acl = json_encode($acls);
            $user->save();
        }

        return true;
    }

    public static function removeAcl($user_id,$acl){
        $user = self::findById($user_id);
        if (!$user) return false;
        $acls = $user->acls();
        if (in_array($acl,$acls)) {
            $key = array_search($acl,$acls);
            unset($acls[$key]);
            $user->acl = json_encode($acls);
            $user->save();
        }
    }
}