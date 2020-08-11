<?php 
	/**
	 * 
	 */
	class Customers extends CI_Controller
	{
		protected $_data;

		function __construct()
		{
			parent::__construct();
			$this->load->helper('url');
			$this->load->helper('security');
			$this->load->model('Mcustomers');
			$this->load->library('session');
			$this->load->library('form_validation');
		}
		public function index()
		{
			$this->_data['page_title'] = 'List Customers';
			$this->_data['head_title'] = 'Manager Customer';
			$this->load->view('admin/customers/index.php', $this->_data);
		}
		public function ajax_list()
		{
			$list = $this->Mcustomers->get_datatables();
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $customers) {
				$no++;
				$row = array();
				$row[] = $customers->id;
				$row[] = $customers->name;
				$row[] = $customers->email;
				$row[] = $customers->phone;
				$row[] = $customers->address;
				$row[] = number_format($customers->amount). ' đ';
				$row[] = $customers->create_at;

            	//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_cus('."'".$customers->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Sửa </a>
				<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_cus('."'".$customers->id."'".')"><i class="glyphicon glyphicon-trash"></i> Xóa</a>';

				$data[] = $row;
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Mcustomers->count_all(),
				"recordsFiltered" => $this->Mcustomers->count_filtered(),
				"data" => $data,
			);
			// json_encode để convert giá trị chỉ đinh thành định dạng JSON (javascript object notation) 
        	//output to json format
			echo json_encode($output);
		}
		public function ajax_add()
		{
			$data = array('status_nofi' => FALSE, 'messages' => array());
			$this->form_validation->set_rules('name', 'name', 'required|trim');
			$this->form_validation->set_rules('email', 'email', 'trim|required|max_length[254]|valid_email');
			$this->form_validation->set_rules('phone','phone','required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_rules('password','password','trim|min_length[6]|max_length[20]|required');
			$this->form_validation->set_rules('address', 'address', 'required|trim');
			$test = $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'name'				=> $this->input->post('name'),
					'email'				=> $this->input->post('email'),
					'phone'				=> $this->input->post('phone'),
					'password'			=> md5($this->input->post('password')),
					'address'			=> $this->input->post('address'),
					'create_at'			=> date('Y-m-d H:i:s')
				);
				$insert = $this->Mcustomers->save($data);
				$data['status_nofi']	= TRUE;
			}
			else {
				$data['messages'] 		=	array(
					'name'				=>  form_error('name'),
					'email'				=> 	form_error('email'),
					'phone'				=> 	form_error('phone'),
					'password'			=> 	form_error('password'),
					'address'			=> 	form_error('address')
				);
			}

				echo json_encode($data);

		}
		public function ajax_exit($id)
		{
			$data = $this->Mcustomers->get_by_id($id);
			$data->create_at = ($data->create_at == '0000-00-00') ? '' : $data->create_at;
			echo json_encode($data);
		}
		public function ajax_update()
		{
			$data = array('status_nofi' => FALSE, 'messages' => array());
			$this->form_validation->set_rules('name', 'name', 'required|trim');
			$this->form_validation->set_rules('address', 'address', 'required|trim');
			$this->form_validation->set_rules('email', 'email', 'required|trim|max_length[254]|valid_email');
			$this->form_validation->set_rules('password', 'password', 'trim|min_length[6]|max_length[20]|required');
			$this->form_validation->set_rules('phone', 'phone', 'required|regex_match[/^[0-9]{10}$/]');
			$this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
			if ($this->form_validation->run() == TRUE) {
				$data = array(
					'name' 		=> 	$this->input->post('name'),
					'email'		=> 	$this->input->post('email'),
					'phone'		=> 	$this->input->post('phone'),
					'address'	=>	$this->input->post('address'),
					'update_at'	=> 	date('Y-m-d h:i:s')

				);
				if ($this->input->post('password')) {
					$data['password'] = md5($this->input->post('password'));
				}
				$this->Mcustomers->update(array('id' => $this->input->post('id')), $data);
				$data['status_nofi'] = TRUE;
			}
			else {
				$data['messages'] = array(
					'name' 		=> form_error('name'),
					'email'		=> form_error('email'),
					'phone'		=> form_error('phone'),
					'address'	=> form_error('address'),
					'password'	=> form_error('password')
				);
			}
			echo json_encode($data);
		}
		public function ajax_delete($id)
		{
			$cus = $this->Mcustomers->get_by_id($id);
			$this->Mcustomers->delete_by_id($id);
			echo json_encode(array("status" => TRUE));
		}
	}
 ?>