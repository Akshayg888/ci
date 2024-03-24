<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
    .alert {
        margin-top: 25px;
    }
</style>
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
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
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
                <div class="card-header">
                    <div class="col-md-offset-6 col-md-6">
                    </div>
                    <div class="col-md-offset-6 col-md-6" align="right">

                        <a href="<?php echo base_url();?>products/add/">
                            <button type="button" class="btn btn-primary">Add Product</button>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="23%">Name</th>
                                    <th width="15%">Price</th>
                                    <th width="15%">Status</th>
                                    <th width="15%">Product Images</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                if (sizeof($product_list) > 0) {
                                    $k = 1;
                                    $ci =& get_instance();
                                    $ci->load->model('products_model');

                                    foreach($product_list AS $row){
                                        $product_image = $ci->products_model->get_product_image($row['product_id']);

                                        ?>
                                        <tr>
                                            <td> <?php echo $k; ?> </td>
                                            <td> <?php echo $row['product_name']; ?> </td>
                                            <td> <?php echo $row['product_price']; ?> </td>
                                            <td> <?php echo $row['status']; ?> </td>
                                            <td>
                                                <a href="javascript:void(0)" onclick="view_images(<?php echo $row['product_id'];?>)" title="View Images" alt="View Images">View Images</a>
                                            </td>
                                            <td>

                                                <a style="background: purple;" href="<?php echo base_url();?>products/edit/<?php echo $row['product_id']; ?>" class="btn default btn-xs" title="Edit Product" alt="Edit Product"><i class="fa fa-edit" style="color:#FFF;"></i></a>
                                                
                                                <a style="background: red;" href="javascript:void(0)" onclick="delete_product(<?php echo $row['product_id'];?>)" class="btn default btn-xs red" title="Delete Product" alt="Delete Product"><i class="fa fa-trash" style="color:#FFF;"></i></a>
                                                
                                            </td>
                                        </tr>
                                        <?php
                                        $k++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_product" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete product</h4>
            </div>
            <div class="modal-body">
                Are you sure to delete this product?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="yes">Yes</button>
                <button type="button" class="btn btn-light" id="no" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Product Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="imageSlider" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner"> </div>
                    <a class="carousel-control-prev" href="#imageSlider" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#imageSlider" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                <span id="imageCounter" class="ml-auto"></span>
            </div>
        </div>
    </div>
</div>

<script>
    function view_images(product_id) {

        var url = '<?php echo base_url() ?>'+'products/view_images/'+product_id;
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    $('#imageSlider .carousel-inner').empty();

                    response.images.forEach(function(imageUrl, index) {
                        var activeClass = index === 0 ? 'active' : '';
                        $('#imageSlider .carousel-inner').append(
                            `<div class="carousel-item ${activeClass}" style="text-align: center;">
                            <img class="d-inline-block" src="${imageUrl}" alt="Slide ${index}" style="max-width: 40%; max-height: 40%; width: auto; height: auto; margin: 0 auto;">
                            </div>`
                            );
                    });
                    $('#imageCounter').text(1 + ' / ' + response.images.length);
                    $('#imageSlider').on('slid.bs.carousel', function () {
                        var slideIndex = $('#imageSlider .carousel-item.active').index() + 1;
                        $('#imageCounter').text(slideIndex + ' / ' + response.images.length);
                    });

                    $('#imageModal').modal();
                } else {
                    alert('Failed to load images: ' + response.message);
                }
            }
        });
    }
</script>
<script type="text/javascript">
    function delete_product(product_id) 
    {
        $('#delete_product').modal();
        $('#delete_product #yes').off().click(function(){
            var url = '<?php echo base_url() ?>'+'products/close';
            $.post(url,
            {
                product_id:product_id,
            },function(responseText){
                if(responseText == 1){
                    location.href = '<?php echo base_url() ?>'+'products';
                } else {
                    alert('Something went wrong with you');
                }
            });
        });
    }
</script>