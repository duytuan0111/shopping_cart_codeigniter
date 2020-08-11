<?php 
/**
 * 
 */
class Get_data
{
	public function get_name_cus_by($id)
	{
		$CI =& get_instance();
		$CI->load->model('Mcustomers');
		$row = $CI->Mcustomers->get_customers_by('id', $id);
		return $row['name'];
	}
	public function get_name_pack_by($id)
	{
		$CI =& get_instance();
		$CI->load->model('Mpackages');
		$row = $CI->Mpackages->get_packages_by('id', $id);
		return $row['name'];
	}
	public function get_amount_cus_by($id)
	{
		$CI =& get_instance();
		$CI->load->model('Mcustomers');
		$row = $CI->Mcustomers->get_customers_by('id', $id);
		return $row['amount'];
	}
	public function get_name_pay_by($id)
	{
		$CI =& get_instance();
		$CI->load->model('Mpayments');
		$row = $CI->Mpayments->get_payments_by('id', $id);
		return $row['name'];
	}
	public function get_price_pack_by($id)
	{
		$CI =& get_instance();
		$CI->load->model('Mpackages');
		$row = $CI->Mpackages->get_packages_by('id', $id);
		return $row['price'];
	}
	public function get_total_inv($id)
	{
		$CI =& get_instance();
		$CI->load->model('Minvoices');
		$row = $CI->Minvoices->get_invoices_by($id);
		return $row['total'];
	}
	public function get_cus_inv($id)
	{
		$CI =& get_instance();
		$CI->load->model('Minvoices');
		$row = $CI->Minvoices->get_invoices_by($id);
		return $row['customer_id'];
	}
	public function get_current_cus()
	{
		$CI =& get_instance();
		$CI->load->model('Mcustomers');
		$login = $CI->session->userdata('user_info');
		if (!empty($login['email'])) {
			$info_user = $CI->Mcustomers->get_cus_info($login['email']);
			if ($info_user) {
				return $info_user['id'];
			}
		}
	}
	public function get_current_user()
	{
		$CI =& get_instance();
		$CI->load->model('Musers');
		$login = $CI->session->userdata('user_login_admin_covid');
		if (!empty($login['username'])) {
			$info_user = $CI->Musers->get_user_info($login['username']);
			if ($info_user) {
				return $info_user['id'];
			}
		}
	}
	

}

 ?>