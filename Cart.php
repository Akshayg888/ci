<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

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
	    $this->load->model('cart_model');
        $this->load->library('curl');

	}

	// all cart item listing using api call
	public function index() {
		
		$data['breadcrumb'] = '<ol class="breadcrumb">
	            <li class="breadcrumb-item"><a href="'.base_url().'">Home</a></li>
	            <li class="breadcrumb-item active">Cart Managment</li>
	        </ol>';

    	$data['pagetitle'] = "Cart Managment";


        $apiUrl = base_url().'/api/cart/cart_list';
        
        $response = $this->curl->simple_get($apiUrl);

        if (!empty($response)) {
            $cart_list = json_decode($response, TRUE);
            $data['cart_list'] = $cart_list['data'];
			$this->layout->load_layout('cart/cart_listing', $data);

        } else {
        	redirect('');
        }
    }


	// remove cart item by id
	public function remove() {

		$cart_id = $this->input->post('cart_id');
		if($cart_id > 0){
			$id = $this->cart_model->remove_product($cart_id);
			if($id > 0){
				$arr_msg = array('suc_msg'=>'Product remove successfully!','msg-type'=>'success');
			}else{
				$arr_msg = array('suc_msg'=>'Failed to remove product','msg-type'=>'danger');
			}
			echo $id;
		}else {
			echo 0;
		}
	}
}