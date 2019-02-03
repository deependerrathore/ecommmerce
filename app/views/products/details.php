<?php $this->setSiteTitle($this->product->title);?>
<?php $this->start('body');?>
<h1><?=$this->product->title?></h1>
<div class="row">
    <!-- Column 1 -->
    <div class="col-md-6">
        <div class="product_image_wrapper">
            <img src="<?=PROJECT_ROOT?>img/catan.jpg" alt="catan">
        </div>
    </div>
    <!-- Column 2 -->
    <div class="col-md-6">
        <p><span>List Price: </span><?=$this->product->list_price?></p>
        <p><span>Our Price: </span><?=$this->product->price?></p>
        <p><span>Shipping: </span><?=$this->product->shipping?></p>
        <p><span>Total: </span><?=$this->product->totalPrice()?></p>
        <p><span>Vendor: </span><?=$this->product->vendor?></p>
        <p><span>Brand: </span><?=$this->product->brand?></p>
        <div class="text-center">   
            <button class="btn btn-large btn-danger" onclick="console.log('Add to class')">
            <i class="fa fa-cart-plus" aria-hidden="true"></i> Add to cart
            </button>
        </div>
    </div>
</div>
<div class="row">
    <!-- Product Description -->
    <div class="col-md-6">
        <h3>Prodct Description</h3>
        <p>
            <?= nl2br($this->product->description)?>
        </p>
    </div>
    <!-- Review Comments -->
    <div class="col-md-6">
        <h3>Customer Reviews</h3>
    </div>
</div>
<?php $this->end();?>