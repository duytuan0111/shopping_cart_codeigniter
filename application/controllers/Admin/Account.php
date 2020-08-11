<?php 
	/**
	 * 
	 */
	class Account extends CI_Controller
	{
		protected $_data;
		
		function __construct()
		{
			parent::__construct();
			$this->load->helper('url');
			$this->load->library('session');
			$this->load->library('form_validation');
			$this->load->library('cart');
			$this->load->Model('admin/Musers_admin', 'Madmin');
		}
		public function index()
		{
			$this->_data['page_title'] 	= "Quản lí quản trị viên | BrotherHood Việt Nam";
			$this->_data['head_title']	= "Danh sách quản trị viên";
			$this->_data['list_admin']	= $this->Madmin->get_all_customers();
			$this->load->view('admin/account/index.php', $this->_data);
		}
		/* Ajax đổ về dữ liệu bảng */
		public function ajax_list()
		{
			$list = $this->Madmin->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $account) {
				$no++;
				$row = array();
				$row[] = '<th><input type="checkbox" id="check-all" class="delete_checkbox" value="'.$account->id.'" ></th>';
				$row[] = $account->id;
				$row[] = $account->name;
				$row[] = $account->username;
				$row[] = $account->last_login;
            	//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="exit_acc('."'".$account->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Sửa </a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_acc('."'".$account->id."'".')"><i class="glyphicon glyphicon-trash"></i> Xóa</a>';

				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Madmin->count_all(),
				"recordsFiltered" => $this->Madmin->count_filtered(),
				"data" => $data,
			);
			// json_encode để convert giá trị chỉ đinh thành định dạng JSON (javascript object notation) 
        	//output to json format
			echo json_encode($output);
		}

		/* Ajax thêm bản ghi */
		public function ajax_add()
		{
			$data = array('status_nofi' => FALSE, 'messages' => array());
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[50]');
			$this->form_validation->set_rules('username', 'username', 'required|trim|max_length[50]|is_unique[admin_users.username]');
			$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[8]|max_length[50]');
			$this->form_validation->set_rules('password_confirm', 'confirm password', 'required|trim|matches[password]');
			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'name' 			=> $this->input->post('name'),
					'username'		=> $this->input->post('username'),
					'password'		=> $this->input->post('password'),
					'created_at'	=> date('Y-m-d H:i:s')
				);
				$insert = $this->Madmin->save($data);
				$data['status_nofi'] = TRUE;
			}
			else {
				$data['messages']	= array(
					'name'			=> form_error('name'),
					'username'		=> form_error('username'),
					'password'		=> form_error('password')
				);
			}
			echo json_encode($data);

		}
		public function ajax_exit($id)
		{
			$data = $this->Madmin->get_by_id($id);
			$data->created_at = ($data->created_at = '0000-00-00') ? '' : $data->created_at;
			echo json_encode($data);
		}
		public function ajax_update()
		{
			$data = array('status_nofi' => FALSE, 'messages' => array());
			$this->form_validation->set_rules('name', 'name', 'required|trim|max_length[50]');
			// $this->form_validation->set_rules('username', 'username', 'required|trim|max_length[50]|is_unique[admin_users.username]');
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', 'password', 'required|trim|min_length[8]|max_length[50]');
				$this->form_validation->set_rules('password_confirm', 'confirm password', 'required|trim|min_length[8]|max_length[50]|matches[password]');
			}
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'name' 	=>	$this->input->post('name')
				);
				if ($this->input->post('password')) {
					$data['password']	= $this->input->post('password');
				}
				$this->Madmin->update(array('id' => $this->input->post('id')), $data);
				$data['status_nofi'] 	= TRUE;

			}
			else {
				$data['messages']	= array(
					'name'		=> form_error('name'),
					'password'	=> form_error('password')
				);
			}
			echo json_encode($data);
		}
		public function delete_all()
		{
			if ($this->input->post('checkbox_value')) {
				$id = $this->input->post('checkbox_value');
				for ($i=0 ; $i < count($id) ; $i++ ) { 
					$this->Madmin->delete_by_id($id[$i]); 
				}
			}
		}
		public function insert()
		{
			$data = array(
				array(
					'id' => '1',
					'name' => 'Viet Nam Khong So Trung Quoc',
					'price' => '10000',
					'qty'   => '1',
					'options' => array('author' =>  'freetuts.net')  
				),
				array(
					'id' => '2',
					'name' => 'Trung Quoc Vi Pham Chu Quyen Viet Nam',
					'price' => '20000',
					'qty'   => '1',
					'options' => array('author' =>  'freetuts.net')
				),
				array(
					'id' => '3',
					'name' => 'Tau Trung Quoc Lien Tuc Dam vao Tau Viet Nam',
					'price' => '30000',
					'qty'   => '1',
					'options' => array('author' =>  'freetuts.net')
				),
			);
			// them san pham vao gio hang
			if ($this->cart->insert($data)) {
				echo "Them San pham thanh cong";
			}
			else {
				echo "Them san pham that bai";
			}
		}
		public function update_cart()
		{
			
		}
		public function show()
		{
			// show thong tin chi tiet gio hang
			$data = $this->cart->contents();
			echo '<pre>';
			print_r($data);
			echo '</pre>';
		}
		// Xóa 1 sp
		public function deleteOne()
		{
			$data = $this->cart->contents();
			foreach ($data as $item) {
				if ($item['id'] 	== '1') {
					$item['qty'] 	= 0;
					$delOne 		= array('rowid' => $item['rowid'], 'qty' => $item['qty']);
				}
			}
			if ($this->cart->update($delOne)) {
				echo 'xóa sp thành công !';
			}
			else {
				echo 'xóa sp thất bại';
			}
		}
		// xóa tất cả sp
		public function del()
		{
			$this->cart->destroy();
			echo "done";
		}

	}
	?>