<?php 

namespace Core\Validators;

use \Exception;
abstract class CustomValidator{
    public $success = true,$msg = '' , $field , $rule;
    protected $_model;

    public function __construct($model, $params){
        $this->_model = $model;

        //make sure the field exists in params
        if(!array_key_exists('field',$params)){
            throw new Exception("You must add a field to the params array");
        }else{
            $this->field = (is_array($params['field'])) ? $params['field'][0] : $params['field'];
        }

        //make sure the field exists in model
        if (!property_exists($model,$this->field)) {
            throw new Exception("The field must exist in the model");
        }

        //make sure the msg exists in params
        if(!array_key_exists('msg',$params)){
            throw new Exception("You must add a msg to the params array");
        }else{
            $this->msg = $params['msg'];
        }

        //setting up the rule value if passed
        if(array_key_exists('rule',$params)){
            $this->rule = $params['rule'];
        }

        try{    
            $this->success = $this->runValidation();
        }catch(Exception $e){
            echo "Validation Exception on " . get_class() . ": " . $e->getMessage() . "<br>";
        }
    }

    abstract public function runValidation();
}