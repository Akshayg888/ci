<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{
        $data['pagetitle'] = "Add Product";

        if (trim($this->input->post('submit')) == '') {

        	$this->load->view('welcome_message',$data);
        } elseif (trim($this->input->post('submit')) == 'Add Product') {
        	// echo "<pre>";print_r($_FILES);die();
			$this->form_validation->set_rules('product_name', 'product name', 'trim|required');
			$this->form_validation->set_rules('product_price', 'product price', 'trim|numeric|required|greater_than[0]');
        	
        	if ($this->form_validation->run($this) == FALSE) {

				$data['product_name'] = trim($this->input->post('product_name'));
				$data['product_price'] = trim($this->input->post('product_price'));

				$this->load->view('welcome_message',$data);
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
}
