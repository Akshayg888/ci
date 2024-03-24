<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	function __construct() {
	    parent::__construct();
	    $this->load->model('products_model');
	}

	// all product listing
	public function index()
	{
		$data['breadcrumb'] = '<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="'.base_url().'">Home</a></li>
                <li class="breadcrumb-item active">Product Managment</li>
            </ol>';
        $data['pagetitle'] = "Product Managment";

		$product_list = $this->products_model->get_product_details();
        $data['product_list'] = $product_list;
        
        if($data != false)
		{
			$this->layout->load_layout('product/product_listing',$data);
		}
	}

	// add new product
	public function add()
	{
		$data['breadcrumb'] = '<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="'.base_url().'">Home</a></li>
                <li class="breadcrumb-item active"><a href="'.base_url().'products">Product Managment</a></li>
                <li class="breadcrumb-item active">Add Product</li>
            </ol>';
        $data['pagetitle'] = "Add Product";

        if (trim($this->input->post('submit')) == '') {

        	$this->layout->load_layout('product/add_product',$data);
        } elseif (trim($this->input->post('submit')) == 'Add Product') {
        	// echo "<pre>";print_r($_FILES);die();
			$this->form_validation->set_rules('product_name', 'product name', 'trim|required');
			$this->form_validation->set_rules('product_price', 'product price', 'trim|numeric|required|greater_than[0]');
        	if ($this->form_validation->run($this) == FALSE) {

				$data['product_name'] = trim($this->input->post('product_name'));
				$data['product_price'] = trim($this->input->post('product_price'));

				$this->layout->load_layout('product/add_product',$data);
			} else {
				$data_insert = $product_image = array();

				if (!empty($_FILES['product_image']['name'][0])) {
			        
			        $filesCount = count($_FILES['product_image']['name']);
			        for ($i = 0; $i < $filesCount; $i++) {
			        	$file_extension = pathinfo($_FILES['product_image']['name'][$i], PATHINFO_EXTENSION);
				        $file_name 	= uniqid() .".".$file_extension;
				        $filetype   = "pdf";
				        $sourcePath = $_FILES['product_image']['tmp_name'][$i];		            
				        $targetPath = "./upload/product/".$file_name;
				        move_uploaded_file($sourcePath, $targetPath);
				        $product_image[$i] = $file_name;
			        }
			    }
				$data_insert['product_name'] = trim($this->input->post('product_name'));
				$data_insert['product_price'] = trim($this->input->post('product_price'));
				$data_insert['status'] = 'Active';

				$id = $this->products_model->add_product($data_insert, $product_image);
				if($id > 0){
					$arr_msg = array('suc_msg'=>'Product Added successfully!','msg-type'=>'success');
				}else{
					$arr_msg = array('suc_msg'=>'Failed to add product','msg-type'=>'danger');
				}
				$this->session->set_userdata($arr_msg);
				redirect('products');
			}
        } else {
			redirect('products');
        }
	}

	// update product details by id
	public function edit($product_id)
	{
		$data['breadcrumb'] = '<ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="'.base_url().'">Home</a></li>
                <li class="breadcrumb-item active"><a href="'.base_url().'products">Product Managment</a></li>
                <li class="breadcrumb-item active">Edit Product</li>
            </ol>';
        $data['pagetitle'] = "Edit Product";

        $product_data = $this->products_model->get_product_details($product_id);
        $data['product_data'] = $product_data;

        $prod_image = $this->products_model->get_product_image($product_id);
        $prod_img = array();
        if (sizeof($prod_image) > 0) {
        	foreach ($prod_image as $key => $value) {
        		$prod_img[$value['pi_id']] = $value['product_image'];
        	}
        }
        $data['prod_img'] = $prod_img;

        // echo "<pre>";print_r($prod);die();
        if (trim($this->input->post('submit')) == '') {

        	$this->layout->load_layout('product/edit_product',$data);
        } elseif (trim($this->input->post('submit')) == 'Edit Product') {

			$this->form_validation->set_rules('product_name', 'product name', 'trim|required');
			$this->form_validation->set_rules('product_price', 'product price', 'trim|numeric|required|greater_than[0]');
        	if ($this->form_validation->run($this) == FALSE) {

				$data['product_name'] = trim($this->input->post('product_name'));
				$data['product_price'] = trim($this->input->post('product_price'));

				$this->layout->load_layout('product/edit_product',$data);
			} else {
				$data_insert = $product_image = array();

				if (!empty($_FILES['product_image']['name'][0])) {
			        $filesCount = count($_FILES['product_image']['name']);
			        for ($i = 0; $i < $filesCount; $i++) {
			        	$file_extension = pathinfo($_FILES['product_image']['name'][$i], PATHINFO_EXTENSION);
				        $file_name 	= uniqid() .".".$file_extension;
				        $filetype   = "pdf";
				        $sourcePath = $_FILES['product_image']['tmp_name'][$i];		            
				        $targetPath = "./upload/product/".$file_name;
				        move_uploaded_file($sourcePath, $targetPath);
				        $product_image[$i] = $file_name;
			        }
			    }

				$data_insert['product_name'] = trim($this->input->post('product_name'));
				$data_insert['product_price'] = trim($this->input->post('product_price'));
				$data_insert['status'] = 'Active';

				$id = $this->products_model->update_product($data_insert, $product_image, $product_id);
				if($id > 0){
					$arr_msg = array('suc_msg'=>'Product update successfully!','msg-type'=>'success');
				}else{
					$arr_msg = array('suc_msg'=>'Failed to update product','msg-type'=>'danger');
				}
				$this->session->set_userdata($arr_msg);
				redirect('products');
			}
        } else {
			redirect('products');
        }
	}

	// In active product by id
	public function close()
	{
		$product_id = $this->input->post('product_id');
		if($product_id > 0){
			$data_insert['status'] = 'In-Active';
			$id = $this->products_model->close_product($data_insert, $product_id);
			if($id > 0){
				$arr_msg = array('suc_msg'=>'Product Closed successfully!','msg-type'=>'success');
			}else{
				$arr_msg = array('suc_msg'=>'Failed to Close Product','msg-type'=>'danger');
			}
			echo 1;
		}else {
			echo 0;
		}
	}

	// view images by product id
	public function view_images($product_id)
	{
		if ($product_id > 0) {
			$prod_image = $this->products_model->get_product_image($product_id);

			if ($prod_image) {
				foreach ($prod_image as $key => $value) {
					$images[$key] = base_url().'/upload/product/'.$value['product_image'];
				}
				$response = [ 'status' => 'success', 'message' => 'images get successfully' , 'code' => '200',  "images" => $images ];
			}else{
				$response = [ 'status' => 'error', 'message' => 'images not found' , 'code' => '404',  "images" => [] ];
			}
		}else{
			$response = [ 'status' => 'error', 'message' => 'product id not found' , 'code' => '404',  "images" => [] ];
			$response = [];
		}
		echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}

	public function image_close()
	{
		$pi_id = trim($this->input->post('imageId'));
		if($pi_id > 0){
			$product_image = $this->products_model->get_product_image('', $pi_id);

			if (!empty($product_image[0]['product_image'])) {
				$path ="./upload/product/".$product_image[0]['product_image'];
			 	unlink($path);
		    }

			$id = $this->products_model->remove_image($pi_id);
			if ($id > 0) {
				echo 1;
			}else{
				echo 0;
			}
		}else {
			echo 0;
		}
	}
}
