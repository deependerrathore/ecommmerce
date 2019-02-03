<?php

namespace App\Models;
use Core\Model;
use Core\Validators\RequiredValidator;
use Core\Validators\NumericValidator;

class Products extends Model{
    public $id, $created_at,$updated_at,$title,$description,$vendor,$brand,$catagory;
    public $list_price,$price,$shipping,$deleted = 0;
    const blackList= ['id','deleted'];
    public function __construct(){
        $table = 'Products';
        parent::__construct($table);
        $this->_softDelete = true;
    }

    public function totalPrice(){
        return $this->price + $this->shipping;
    }

    public function beforeSave(){
        $this->timeStamps();
    }
    public function afterSave(){
        $this->id = $this->_db->lastID();
    }

    public function validator(){
        $requiredFields= ['title'=>'Title','price'=>'Price','list_price'=> 'List Price','shipping'=>'Shipping','description'=>'Description'];
        foreach($requiredFields as $field => $display){
            $this->runValidation(new RequiredValidator($this,['field' => $field,'msg' => $display . " is required."]));
        }
        $this->runValidation(new NumericValidator($this,['field'=>'price', 'msg'=>'Price must be numeric.']));
    }
}