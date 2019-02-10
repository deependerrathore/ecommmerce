<?php 

namespace Core;

/**
 * Class View
 */
class View{
    protected $_head, $_body,$_siteTitle = SITE_TITLE, $_outputBuffer, $_layout = DEFAULT_LAYOUT;

    

    
    /**
     * used to render the layout and view
     * @method render 
     * @param string $viewName pathh of the view
     * @return void
     */
    public function render($viewName){
        $viewArray = explode('/',$viewName);
        $viewString = implode(DS,$viewArray);
        if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $viewString .'.php')) {
            include(ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php');
            include(ROOT . DS . 'app' . DS . 'views' . DS . 'layout' . DS. $this->_layout . '.php');
        }else{
            die("This view {$viewName} does not exist");
        }
    }

    /**
     * Used in the layouts to embet the head and body
     * @method content
     * @param string $type can be head or body
     * @return string returns the output buffer of head and body
     */
    public function content($type){
        if($type == 'head'){
            return $this->_head;
        }elseif($type == 'body'){
            return $this->_body;
        }

        return false;
    }

    /**
     * start the output buffer for the head or body
     * @method start
     * @param string $type
     */
    public function start($type){
        $this->_outputBuffer = $type;
        ob_start(); //start the output buffer
    }

    /**
     * echos the output buffer in the layout
     * @method end
     * @return string rendered html for head and body
     */
    public function end(){
        if($this->_outputBuffer == 'head'){
            $this->_head = ob_get_clean(); //assigning the data to _head and cleaning the ob
        }elseif($this->_outputBuffer == 'body'){
            $this->_body = ob_get_clean(); //assigning the data to _body and cleaning the ob
        }else{
            die('You must first the the strat method');
        }
    }

    /**
     * Getter for the site title
     * @method getSiteTitle
     * @return string site title set in the view object
     */
    public function getSiteTitle(){
        return $this->_siteTitle;
    }
    

    /**
     * Setter for the site title - default site title can be setted up in config file
     * @method setSiteTitle
     * @param string $title title of the site
     */
    public function setSiteTitle($title){
        $this->_siteTitle = $title;
    }

    
    /**
     * sets the layout to be loaded - default is setted up in config file
     * @method setLayout
     * @param string $path name of the layout
     */
    public function setLayout($path){
        $this->_layout = $path;
    }

    /**
     * inserts a partial into another partial
     * @method insert
     * @param string $path path to view example register/register
     */
    public function insert($path){
        include(ROOT. DS . 'app' . DS . 'views' . DS . $path . '.php' );
    }

    /**
     * inserts a partial into a view    
     * @method partial
     * @param string $group view sub directory
     * @param string $partial partial name
     */
    public function partial($group,$partial){
        include(ROOT. DS . 'app' . DS . 'views' . DS . $group . DS . 'partials' . DS . $partial . '.php' );
    }

}