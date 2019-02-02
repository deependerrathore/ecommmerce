<?php use Core\FH;?>
<form class="needs-validation" action="<?=$this->postAction?>" method="POST" enctype="multipart/form-data">
    <div class="form-row">
        <?=FH::csrfInput();?>
        <?=FH::inputBlock('text','Title','title',$this->product->title,'Product Name/Title',['class'=>'form-control input-sm'],['class'=>'form-group col-md-6 mb-3'],$this->displayErrors);?>
        <?=FH::inputBlock('text','Price','price',$this->product->price,'Product Actual Price',['class'=>'form-control input-sm'],['class'=>'form-group col-md-2 mb-3'],$this->displayErrors);?>
        <?=FH::inputBlock('text','List Price','list_price',$this->product->list_price,'Product List Price',['class'=>'form-control input-sm'],['class'=>'form-group col-md-2 mb-3'],$this->displayErrors);?>
        <?=FH::inputBlock('text','Shpping','shipping',$this->product->shipping,'Shapping Charge',['class'=>'form-control input-sm'],['class'=>'form-group col-md-2 mb-3'],$this->displayErrors);?>
        <?=FH::submitBlock('Save',['class'=>'btn btn-primary btn-md float-right'],['class'=>'form-group col-md-12']); ?>
    </div>
     
</form>