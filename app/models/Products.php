<?php

namespace App\Models;
use Core\Model;

class Products extends Model{
    public $id, $craeted_at,$updated_at,$title,$description,$vendor,$brand,$catagory;
    public $list_price,$price,$shipping,$deleted = 0;
    public function __construct(){
        $table = 'Products';
        parent::__construct($table);
        $this->_softDelete = true;
        
    }
}