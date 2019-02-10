<?php

namespace Core;
use Core\H;
/**
 * Parent class for App Models
 */
class Model{
    protected $_modelName,$_validates=true, $_validationErrors=[];
    public $id;

    protected static $_db,$_table,$_softDelete=false;

    public function __construct(){
        $this->_modelName = str_replace(' ','',ucwords(str_replace('_',' ',static::$_table))); //example user_sessions = UserSessions
    }

    public static function getDb(){
        if (!self::$_db) {
            self::$_db = DB::getInstance();
        }
        return self::$_db;
    }
    /**
     * Query database for model to get column information
     * @method get_columns
     * @return object  columns object
     */
    protected static function get_columns(){
        return static::getDB()->get_columns(static::$_table);
    }
    
    /**
     * gets an associative array of field values for insert or update
     * @method getColumnsForSave
     * @return array associate array of the fields from database and values from model object
     */
    public function getColumnsForSave(){
        $columns = static::get_columns();
        $fields = [];
        foreach($columns as $column){
            $key = $column->Field;
            $fields[$key] = $this->{$key};
        }


        return $fields;
    }

    /**
     * adds to the conditions to avoid getting soft deleted rows returned
     * @method _softDeleteParams    
     * @param array $params defined parameter to search by
     * @return array $params parameters with appended conditions for soft delete
     */
    protected static function _softDeleteParams($params){
        if (static::$_softDelete) {
            if (array_key_exists('conditions',$params)) {
                if (is_array($params['conditions'])) {
                    $params['conditions'][] = "deleted != 1";
                    
                }else{
                    $params['conditions'] .= ' AND deleted != 1';
                }
            }else{
                $params['conditions'] = "deleted != 1";
            }
        }
        return $params;
    }

    /**
     * Find a result set
     * @method find
     *
     * @param array $params conditions
     * @return array array of rows or an empty if none found
     */
    public static function find($params = []){
        $params = static::_softDeleteParams($params);
        $resultsQuery = static::getDb()->find(static::$_table,$params,static::class);
        if (!$resultsQuery) return [];
        return $resultsQuery;
    }
    
    /**
     * Find the first object that matches the condtions
     * @method findFirst
     * @param array $params array of condtions and binds
     * @return object | false returns Model object or false if one is not found
     */
    public static function findFirst($params = []){
        $params = static::_softDeleteParams($params);
        $resultQuery = static::getDb()->findFirst(static::$_table,$params,static::class);
        if (!$resultQuery) return false;
        return $resultQuery;
    }

    /**
     * Finds a row for this model by id
     * @method findById 
     * @param integer $id if of the object to return
     * @return object Model object
     */
    public static function findById($id){
        return static::findFirst([
            'conditions' => ['id = ?'],
            'bind' => [$id]
        ]);
    }

    /**
     * save funtion inserts or updates the record based opon condtion if id already exists in database or not
     * Also it run the validator in extended classed so only if validator passes then only it will save the record
     * @method save 
     * @return boolean 
     */
    public function save(){

        $this->validator();
        $save = false;
        if($this->_validates){
            
            $this->beforeSave();
            
            $fields = $this->getColumnsForSave();
            
            //Determine whether to update or insert
            if($this->isNew()){
                $save = $this->insert($fields);
                if ($save) {
                    $this->id = static::getDb()->lastID();
                }
            }else{
                $save = $this->update($fields);
            }
            if ($save) {
                $this->afterSave();
            }
        }

        return $save;
    }

    /**
     * Insert a row into the database
     *
     * @param [type] $fields
     * @return void
     */
    public function insert($fields){
        if(empty($fields)) return false;
        if(array_key_exists('id', $fields)) unset($fields['id']);
        return static::getDb()->insert(static::$_table,$fields);
    }


    public function update($fields){
        if (empty($fields) || $this->id == '') return false;
        return static::getDb()->update(static::$_table,$this->id,$fields);
    }

    public function delete(){
        if($this->id == '' || !isset($this->id)) return false;

        $this->beforeDelete();

        if (static::$_softDelete) {
            $deleted = $this->update(['deleted' => 1]);
        } else {
            $d = static::getDb()->delete(static::$_table, $this->id);
        }
        
        $this->afterDelete();

        return $deleted;
        
    }

    public function query($sql,$bind = []){
        return static::getDB()->query($sql,$bind);
    }

    public function data(){
        $data = new \stdClass();
        foreach(static::get_columns() as $column){
            $columnName = $column->Field;
            $data->{$columnName} = $this->{$columnName};
        }

        return $data;
    }

    public function assign($params = [],$list=[],$blackList = true){

        if(!empty($params)){
            foreach($params as $key => $value){
                //check if there is permission to update the object
                $whiteListed =  true;
                if(sizeof($list) > 0){
                    if ($blackList) {
                        $whiteListed = !in_array($key,$list);
                    }else{
                        $whiteListed = in_array($key,$list);
                    }
                }
                if(property_exists($this,$key) && $whiteListed){
                    $this->$key = $value;
                }
            }
        }
        return $this;
    }
    
    
    public function validator(){}

    public function runValidation($validator){
        $key = $validator->field;
        if(!$validator->success){
            $this->_validates = false;
            $this->addErrorMessage($key,$validator->msg);
        }

    }
    
    public function getErrorMessages(){
        return $this->_validationErrors;
    }

    public function validationPassed(){
        return $this->_validates;
    }

    public function addErrorMessage($field,$msg){
        $this->_validates = false;
        if(array_key_exists($field,$this->_validationErrors)){
            $this->_validationErrors[$field] .= " " . $msg;
        }else{
            $this->_validationErrors[$field] = $msg;
        }
    }

    public function beforeSave(){}
    
    public function afterSave(){}

    /**
     * Runs before delete needs to return a boolean
     *
     * @return boolean
     */
    public function beforeDelete(){}
    
    public function afterDelete(){}

    public function timeStamps(){
        date_default_timezone_set("UTC"); 

        $now = date('Y-m-d H:i:s');
        $this->updated_at = $now;
        if (empty($this->id)) {
            $this->created_at = $now;
        }
    }

    public function isNew(){
        return (property_exists($this,'id') && !empty($this->id)) ? false : true;
    }

}