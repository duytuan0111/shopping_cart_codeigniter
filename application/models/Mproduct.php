<?php 
/**
 * 
 */
class Mproduct extends CI_Model
{
	
	protected $_table = 'product';

	function get_all_product() {
		$this->db->order_by('product_id', 'desc');
		return $this->db->get($this->_table)->result_array();
	}

}
 ?>