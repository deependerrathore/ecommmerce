<?php $this->setSiteTitle("Catan");?>
<?php $this->start('body');?>
<h1>Catan</h1>
<div class="row">
    <!-- Column 1 -->
    <div class="col-md-6">
        <div class="product_image_wrapper">
            <img src="<?=PROJECT_ROOT?>img/catan.jpg" alt="catan">
        </div>
    </div>
    <!-- Column 2 -->
    <div class="col-md-6">
        <p><span>Price: </span>$50.00</p>
        <p><span>Shipping: </span>$4.00</p>
        <p><span>Total: </span>$54.00</p>
        <p><span>Vendor: </span>Flipkart</p>
        <p><span>Brand: </span>JJ</p>
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
            Catan is one of the funniest board cames you will ever play..enjoyed by millions WORLD WIDE.
        </p>
    </div>
    <!-- Review Comments -->
    <div class="col-md-6">
        <h3>Customer Reviews</h3>
    </div>
</div>
<?php $this->end();?>