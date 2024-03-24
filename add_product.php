<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <?php echo $pagetitle; ?>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <?php echo $breadcrumb; ?>
        </div>
    </div>
    <!-- row -->
    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <div class="card-body">
                    <div class="basic-form">
                        <?php
                            $attributes = array('class' => 'horizontal-form', 'id' => 'myform');
                            echo form_open_multipart('products/add',$attributes);

                            $product_name = set_value('product_name');
                            $data_product_name = array(
                                'name'          => 'product_name',
                                'id'            => 'product_name',
                                'value'         => $product_name,
                                'class'         => 'form-control',
                                'required'      => 'required',
                                'placeholder'   => 'Product Name'
                            );

                            $product_price = set_value('product_price');
                            $data_product_price = array(
                                'name'          => 'product_price',
                                'type'          => 'number',
                                'step'          => 'any',
                                'id'            => 'product_price',
                                'value'         => $product_price,
                                'class'         => 'form-control',
                                'required'      => 'required',
                                'placeholder'   => 'Price'
                            );

                            $product_image = set_value('product_image');
                            $data_product_image = array(
                                'name'          => 'product_image[]',
                                'id'            => 'product_image',
                                'type'          => 'file',
                                'value'         => $product_image,
                                'class'         => 'form-control',
                                'required'      => 'required',
                                'placeholder'   => 'product_image',
                                'multiple'      => 'multiple',
                                'onchange'      => 'validateFileType()'
                            );

                            $arr_submit = array(
                                'name' => 'submit',
                                'value' => $pagetitle,
                                'class' => 'btn btn-primary'
                            );
                            ?>
                            <?php 
                            if(isset($_SESSION['suc_msg'])){ ?>
                                <div class="col-md-12 col-sm-12"> 
                                    <div class="alert alert-<?php echo $_SESSION['msg-type']; ?>">
                                        <?php echo $_SESSION['suc_msg'];
                                        unset($_SESSION['suc_msg']); unset($_SESSION['msg-type']);
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="product_name">Product Name<span class="required" aria-required="true"> *</span></label>
                                        <?php echo form_input($data_product_name); ?>
                                        <span class="help-block help-block-error" for="product_name" style="color:#F30;"><?php echo form_error('product_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="product_price">Product Price<span class="required" aria-required="true"> *</span></label>
                                        <?php echo form_input($data_product_price); ?>
                                        <span class="help-block help-block-error" for="product_price" style="color:#F30;"><?php echo form_error('product_price'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="product_image">Product image<span class="required" aria-required="true"> *</span></label>
                                        <?php echo form_input($data_product_image); ?>
                                            <span class="help-block help-block-error" id="fileError" style="color:#F30;"></span>
                                        
                                        <span class="help-block help-block-error" for="product_image" style="color:#F30;"><?php echo form_error('product_image'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-6 col-md-12" align="right">
                                                <span><?php echo form_submit($arr_submit)?></span>
                                                <a href="<?php echo base_url();?>products">
                                                    <button type="button" class="btn btn-light">Cancel</button>
                                                </a>
                                            </div>
                                            <div class="col-md-6"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php   
                        echo form_close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function validateFileType() {
    var inputFile = document.getElementById('product_image');
    var files = inputFile.files;
    var validExtensions = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    var errorMessage = document.getElementById('fileError');

    for (var i = 0; i < files.length; i++) {
        if (validExtensions.indexOf(files[i].type) === -1) {
            errorMessage.innerHTML = "Please select only image files.";
            inputFile.value = '';
            return false;
        }
    }
    errorMessage.innerHTML = "";
    return true;
}
</script>