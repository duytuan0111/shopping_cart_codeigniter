<?php

defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * 
 */

class Product extends CI_Controller
{
	protected $_data;

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('security');
		$this->load->library('cart');
		$this->load->model('Mproduct');	
	}
	public function index() {
		$this->_data['page_title'] 		= 'Shopping Cart php & Jquery';
		$this->_data['head_title'] 		= 'Shoping Cart Demo';
		$this->_data['list_product']	= $this->Mproduct->get_all_product();
		// var_dump($this->_data['list_product']);
		$this->load->view('product_view.php', $this->_data); 
	}
	public function add_to_cart() {
		$data = array(
			'id'  	=> $this->input->post('product_id'),
			'name' 	=> $this->input->post('product_name'),
			'price' => $this->input->post('product_price'),
			'qty'	=> $this->input->post('quantity')
		);
		$this->cart->insert($data);
		echo $this->show_cart();

	}
	public function show_cart() {
		$output = '';
		$no = 0;
		foreach ($this->cart->contents() as $items) {
			$no++;
			$output .= '
				<tr>
					<td>'.$items['name'].'</td>
					<td>'.number_format($items['price']).'</td>
					<td>'.$items['qty'].'</td>
					<td>'.number_format($items['subtotal']).'</td>
					<td><button type="button" id="'.$items['rowid'].'" class="btn btn-sm btn-danger remove_cart">Xóa</button></td>
				</tr>
			';
		}
		$output .= '
			<tr>
				<th colspan="3">Total</th>
				<th colspan="2">'.number_format($this->cart->total()).'$</th>
			</tr>
		';
		echo $output;
	}
	public function load_cart() {
		echo $this->show_cart();
	}
	public function delete_cart() {
		$data = array(
			'rowid' => $this->input->post('row_id'),
			'qty'	=> 0
		);
		$this->cart->update($data);
		echo $this->show_cart();
	}
	public function delete_all() {
		$this->cart->destroy();
		redirect('product/','refresh');
	}


}
 ?>