<?php $this->setSiteTitle('Add Product');?>
<?php $this->start('body');?>
<h1>Add New Product</h1>

<div class="row justify-content-md-center">
    <div class="col-md-10 card card-body bg-light">
        <?php $this->partial('admin/products','form');?>
    </div>
</div>
<?php $this->end();?>