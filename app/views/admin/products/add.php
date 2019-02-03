<?php $this->setSiteTitle('Add Product');?>
<?php $this->start('head');?>
<script src="<?=PROJECT_ROOT?>vendor/tinymce/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#description',
        branding: false
    });
</script>
<?php $this->end();?>
<?php $this->start('body');?>
<h1>Add New Product</h1>

<div class="row justify-content-md-center">
    <div class="col-md-10 card card-body bg-light">
        <?php $this->partial('admin/products','form');?>
    </div>
</div>
<?php $this->end();?>