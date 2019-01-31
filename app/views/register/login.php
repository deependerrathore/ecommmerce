<?php 
use Core\FH;
?>

<?php $this->setSiteTitle('eLive - Login Page');?>
<?php $this->start('head');?>
<?php $this->end();?>
<?php $this->start('body');?>
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 mx-auto">

                    <!-- form card login -->
                    <div class="card rounded-0">
                        <div class="card-header">
                            <h3 class="mb-0">Login</h3>
                        </div>
                        <div class="card-body">
                            <form class="form" role="form" autocomplete="off" action=<?= $this->postAction;?> novalidate="" method="POST">
                                <?=FH::csrfInput();?>
                                <?=FH::inputBlock('text','Username','username',$this->login->username,'Username',['class'=>'form-control form-control-lg rounded-0'],['class' => 'form-group'],$this->displayErrors); ?>
                                <?=FH::inputBlock('password','Password','password',$this->login->password,'Password',['class'=>'form-control form-control-lg rounded-0'],['class' => 'form-group'],$this->displayErrors); ?>
                                
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="remember_me" id="remember_me">
                                    <label class="form-check-label" for="remember_me">Remeber me</label>
                                </div>
                                <?=FH::submitBlock('Login',['class'=>'btn btn-success btn-lg float-right'])?>
                                <a class="btn btn-success btn-lg float-left" href="<?=PROJECT_ROOT?>register/register">Register here</a>

                            </form>
                        </div>
                        <!--/card-block-->
                    </div>
                    <!-- /form card login -->

                </div>


            </div>
            <!--/row-->

        </div>
        <!--/col-->
    </div>
    <!--/row-->
</div>
<!--/container-->
<?php $this->end();?>
