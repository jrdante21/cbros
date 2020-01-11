<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CBROS extends CI_Controller {

	function __construct () {
        parent::__construct(); 
		$this->load->model('Main_Model');
       	$this->load->library('session');
       	$this->load->library('My_Pagination');
		$this->ci_minifier->enable_obfuscator();
	}

    private function active () {
    	if (!$this->session->admincbros || $this->session->admincbros == '') {
    		redirect('/CBROS/');
    	} else {
    		return $this->session->admincbros;
    	}
    }

	public function index ($err = null) {
    	if ($this->session->admincbros) {
    		redirect('/CBROS/home');
    	}
		$data['err'] = $err;
		$this->load->view('admin/login', $data);
	}

	public function login () {
		$user = $this->input->post('username');
		$pwrd = $this->input->post('password');
		$res = $this->Main_Model->login($user, $pwrd);
		if ($res === 'success') {
			redirect('CBROS/home');
		} else {
			redirect('CBROS/index/'.$res);
		}
		
	}

	public function home () {
		$this->active();
		$data['nav'] = 'Home';
		$data['group'] = $this->Main_Model->get_groups();

		$cont['countG'] = $this->Main_Model->get_groups();
		$cont['countE'] = $this->Main_Model->get_employees('count');
		$cont['total'] = $this->Main_Model->total_payouts();

		$data['content'] = $this->load->view('admin/home', $cont, TRUE);
		$this->load->view('admin/navig', $data);
	}

	public function employees () {
		$this->active();
		$data['nav'] = 'Employees';
		$data['group'] = $this->Main_Model->get_groups();

		$cont['group'] = $this->Main_Model->get_groups();

		$data['content'] = $this->load->view('admin/employees', $cont, TRUE);
		$this->load->view('admin/navig', $data);
	}

	public function add_new ($act = null) {
		$this->active();
		switch ($act) {
			case 'employee':
				$data['emply_fname'] = $this->input->post('Addfname');
				$data['emply_mname'] = $this->input->post('Addmname');
				$data['emply_lname'] = $this->input->post('Addlname');
				$data['sex'] = $this->input->post('Addgender');
				$data['position'] = $this->input->post('Addpos');
				$data['designation'] = $this->input->post('Adddesig');
				$data['emply_group'] = $this->input->post('Addgroup');

				$id = $this->Main_Model->add_employee($data);
				redirect('CBROS/employee/profile/'.$id);
				break;
			
			case 'group':
				$data['group_name'] = $this->input->post('AddGroupname');
				$data['date_start'] = $this->input->post('AddGroupdateS');
				$data['date_end'] = $this->input->post('AddGroupdateE');

				$this->Main_Model->add_group($data);
				redirect('CBROS/groups/');
				break;
		}
	}

	public function employee ($act = 'profile', $id = null) {
		$this->active();
		$data['nav'] = 'Employees';
		$data['group'] = $this->Main_Model->get_groups();

		$employee = $this->Main_Model->get_employee($id);
		if (!$employee) {
			redirect('CBROS/employees');
		}

		if ($employee->emply_status == 'TRUE') {
			$cont['stat'] = ['cls' => 'border-bottom-blue', 'btn' => '<button class="uk-button uk-button-danger" id="restoreEmployee">Remove</button>'];
		} else {
			$cont['stat'] = ['cls' => 'border-bottom-red', 'btn' => '<button class="uk-button uk-button-primary" id="restoreEmployee">Restore</button>'];
		}

		$cont['nav'] = ['profile' => ['cls' => 'uk-button-default', 'url' => site_url('CBROS/employee/profile/'.$id)],
						'records' => ['cls' => 'uk-button-default', 'url' => site_url('CBROS/employee/records/'.$id)],
						'advances' => ['cls' => 'uk-button-default', 'url' => site_url('CBROS/employee/advances/'.$id)],
						'savings' => ['cls' => 'uk-button-default', 'url' => site_url('CBROS/employee/savings/'.$id)],
						'payslips' => ['cls' => 'uk-button-default', 'url' => site_url('CBROS/employee/payslips/'.$id)]
						];

		$cont['nav'][$act]['cls'] = 'uk-button-primary';
		$cont['emp'] = $employee;

		switch ($act) {
			case 'profile':
				$cont['groups'] = $this->Main_Model->get_groups();
				$data['content'] = $this->load->view('admin/e_profile', $cont, TRUE);
				break;

			case 'records':
				$data['content'] = $this->load->view('admin/e_records', $cont, TRUE);
				break;
			
			case 'advances':
				$data['content'] = $this->load->view('admin/e_advances', $cont, TRUE);
				break;
			
			case 'savings':
				$data['content'] = $this->load->view('admin/e_savings', $cont, TRUE);
				break;
			
			case 'payslips':
				$data['content'] = $this->load->view('admin/e_payslip', $cont, TRUE);
				break;
			
			default:
				redirect('CBROS/employees');
				break;
		}

		$this->load->view('admin/navig', $data);
	}

	public function groups () {
		$this->active();
		$data['nav'] = 'Groups';
		$data['group'] = $this->Main_Model->get_groups();

		$data['content'] = $this->load->view('admin/groups', null, TRUE);
		$this->load->view('admin/navig', $data);
	}

	public function print ($act = null, $id = null) {
		$this->active();
		switch ($act) {
			case 'employee_payslip':
				$data['rec'] = $this->Main_Model->get_payslips($id);
				$this->load->view('admin/print_payslip_employees', $data);
				break;

			case 'group_payslip':
				$id = $this->input->post('groupPP');
				$sort = $this->input->post('sortPP');
				$order = $this->input->post('orderPP');
				$start = $this->input->post('paginatePP');
				$data['rec'] = $this->Main_Model->get_payslips($id, 'group', $sort, $order , $start);
				$this->load->view('admin/print_payslip_employees', $data);
				break;

			case 'summary_payouts':
				$group = $this->input->post('inputgroupSummary');
				$date = $this->input->post('inputdateSummary');
				$data['date'] = $date;
				$data['rec'] = $this->Main_Model->payout_list_summary($group, $date);
				$this->load->view('admin/print_payout_summary', $data);
				break;
		}
	}

	public function recycle_bin () {
		$this->active();
		$data['nav'] = 'Recycle Bin';
		$data['group'] = $this->Main_Model->get_groups();

		$cont['group'] = $this->Main_Model->get_groups();

		$data['content'] = $this->load->view('admin/employees_recycle', $cont, TRUE);
		$this->load->view('admin/navig', $data);
	}

	public function profile () {
		$cont['admin'] = $this->active();
		$data['nav'] = 'Profile';
		$data['group'] = $this->Main_Model->get_groups();

		$data['content'] = $this->load->view('admin/profile', $cont, TRUE);
		$this->load->view('admin/navig', $data);
	}

	public function profile_ajax_requests ($act = null) {
		$user = $this->active();
		$pwrd = $this->input->post('pwrd');

		switch ($act) {
			case 'username':
				$data['username'] = $this->input->post('user');
				break;
			
			case 'password':
				$data['npwrd'] = $this->input->post('npwrd');
				$data['cpwrd'] = $this->input->post('cpwrd');
				break;
		}

		$res = $this->Main_Model->edit_admin($act, $user, $pwrd, $data);
		echo json_encode($res);
	}

	public function ajax_requests ($act = null, $any = 0) {
		$this->active();
		switch ($act) {
			case 'get_nextprev_employees':
				$id = $this->input->post('emply');
				$group = $this->input->post('group');
				$sort = $this->input->post('sort');
				$order = $this->input->post('order');
				$stat = $this->input->post('stat');

				$res = $this->Main_Model->get_nextprev_employees($id, $group, $sort, $order, $stat);
				echo json_encode($res);
				break;

			case 'get_employees':
				$start = $any;
				$group = $this->input->post('group');
				$sort = $this->input->post('sort');
				$order = $this->input->post('order');
				$search = $this->input->post('search');
				$stat = $this->input->post('stat');

				$data = $this->Main_Model->get_employees('list', $start, $stat, $search, $group, $sort, $order);
				$link = $this->pagination('ajax_requests/get_employees', $this->Main_Model->get_employees('count', 0, 'TRUE', $search, $group));

				echo json_encode(array('data' => $data, 'link' => $link));
				break;

			case 'get_employee':
				$id = $this->input->post('emply');
				$employee = $this->Main_Model->get_employee($id);
				echo json_encode($employee);
				break;

			case 'update_employee':
				$id = $this->input->post('emply');
				$action = $this->input->post('act');
				if ($action == 'general') {
					$data['emply_fname'] = $this->input->post('fname');
					$data['emply_mname'] = $this->input->post('mname');
					$data['emply_lname'] = $this->input->post('lname');
					$data['emply_address'] = $this->input->post('address');
					$data['sex'] = $this->input->post('sex');
					$data['contact'] = $this->input->post('contact');
					$data['bday'] = $this->input->post('bday');

				} elseif ($action == 'gov') {
					$data['sss_id'] = $this->input->post('sss');
					$data['ph_id'] = $this->input->post('ph');
					$data['pagibig_id'] = $this->input->post('pi');

				} elseif ($action == 'work') {
					$data['position'] = $this->input->post('pos');
					$data['designation'] = $this->input->post('desig');
					$data['emply_group'] = $this->input->post('group');

				} elseif ($action == 'emergency') {
					$data['emername'] = $this->input->post('name');
					$data['emercontact'] = $this->input->post('contact');
				}
				$this->Main_Model->update_employee($id, $data); 
				break;

			case 'upload_image':
				$fname = bin2hex(random_bytes(10)).time();
				$config['upload_path'] = 'images/employees/';
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['max_size'] = 10000;
				$config['file_name'] = $fname;
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('photo')) {
					$res = $this->upload->display_errors();
					$msg = ['stat' => 'danger', 'msg' => $res];
				} else {
					$res = $this->upload->data();

					$id = $this->input->post('emply');
					$data['emply_pic'] = $res['file_name'];
					
					$this->Main_Model->update_employee($id, $data); 
					$msg = ['stat' => 'success', 'msg' => 'Photo successfully saved!'];
				}
				echo json_encode($msg);
				break;

			case 'get_records':
				$id = $this->input->post('emply');
				$ds = $this->input->post('ds');
				$de = $this->input->post('de');

				$res = $this->Main_Model->get_records($id, $any, $ds, $de);
				$link = $this->pagination('ajax_requests/get_records', $res['rows']);
				echo json_encode(array('data' => $res['data'], 'link' => $link, 'comp' => $res['comp']));
				break;

			case 'get_last_records':
				$id = $this->input->post('emply');
				$s = $this->input->post('start');
				$e = $this->input->post('end');
				$res = $this->Main_Model->get_last_records($id, $s, $e);
				echo json_encode($res);
				break;
			
			case 'get_advances':
				$id = $this->input->post('emply');
				$ds = $this->input->post('ds');
				$de = $this->input->post('de');
				$res = $this->Main_Model->get_advances($id, $any, $ds, $de);
				$link = $this->pagination('ajax_requests/get_advances', $res['rows']);
				echo json_encode(array('data' => $res['data'], 'link' => $link, 'comp' => $res['comp']));
				break;

			case 'add_advance':
				$id = $this->input->post('emply');
				$gID = $this->input->post('group');
				$date = $this->input->post('date');
				$amount = $this->input->post('amount');

				if (empty($date) || empty($amount) || $amount <= 0) {
					echo json_encode(['msg' => 'Please complete the specified field!', 'stat' => 'danger']);
				} else {
					$res = $this->Main_Model->add_advances($id, $gID, $date, $amount);
					echo json_encode($res);
				}
				break;
			
			case 'delete_advance':
				$id = $this->input->post('id');
				$gID = $this->input->post('group');
				$res = $this->Main_Model->remove_advances($id, $gID);
				echo json_encode($res);
				break;
			
			case 'get_savings':
				$id = $this->input->post('emply');
				$ds = $this->input->post('ds');
				$de = $this->input->post('de');
				$res = $this->Main_Model->get_savings($id, $any, $ds, $de);
				$link = $this->pagination('ajax_requests/get_savings', $res['rows']);
				echo json_encode(array('data' => $res['data'], 'link' => $link, 'comp' => $res['comp']));
				break;

			case 'add_savings':
				$id = $this->input->post('emply');
				$gID = $this->input->post('group');
				$date = $this->input->post('date');
				$amount = $this->input->post('amount');

				if (empty($date) || empty($amount) || $amount <= 0) {
					echo json_encode(['msg' => 'Please complete the specified field!', 'stat' => 'danger']);
				} else {
					$res = $this->Main_Model->add_savings($id, $gID, $date, $amount);
					echo json_encode($res);
				}
				break;
			
			case 'delete_savings':
				$id = $this->input->post('id');
				$gID = $this->input->post('group');
				$res = $this->Main_Model->remove_savings($id, $gID);
				echo json_encode($res);
				break;
			
			case 'update_payslip':
				$id = $this->input->post('emply');
				$s = $this->input->post('start');
				$e = $this->input->post('end');
				$d = $this->input->post('data');
				$this->Main_Model->update_payslip($id, $s, $e, $d);
				break;

			case 'get_groups':
				$res = $this->Main_Model->get_groups();
				echo json_encode($res);
				break;

			case 'update_group':
				$action = $this->input->post('act');
				$gID = $this->input->post('gID');
				if ($action == 'date') {
					$data['date_start'] = $this->input->post('ds');
					$data['date_end'] = $this->input->post('de');
				} elseif ($action == 'name') {
					$data['group_name'] = $this->input->post('name');
				}
				$this->Main_Model->update_group($gID, $data);
				break;

			case 'delete_group':
				$gID = $this->input->post('gID');
				$res = $this->Main_Model->delete_group($gID);
				echo json_encode($res);
				break;

			case 'restore_employee':
				$id = $this->input->post('emply');
				$stat = $this->input->post('stat');
				$this->Main_Model->restore_employee($id, $stat);
				break;

			case 'get_payout_summary':
				$year = $this->input->post('year');
				$res = $this->Main_Model->payout_summary($year);
				echo json_encode($res);
				break;
			
			default:
				echo json_encode(array('msg' => "Unknown request!", 'stat' => 'danger'));
				break;
		}
	}

	private function pagination ($url, $rows) {
        $config['base_url'] = site_url('CBROS/'.$url);
        $config['total_rows'] = $rows;
        $config['per_page'] = 10;
        $config['attributes']['rel'] = FALSE;
        $config['uri_segment'] = 4;
         
        // custom paging configuration
        $config['num_links'] = 10;
        $config['use_page_numbers'] = FALSE;

        $config['full_tag_open'] = '<div class="uk-pagination">';
        $config['full_tag_close'] = '</div>';
         
        $config['first_link'] = '<<';
        $config['first_tag_open'] = '<span class="firstlink">';
        $config['first_tag_close'] = '</span>';
         
        $config['last_link'] = '>>';
        $config['last_tag_open'] = '<span class="lastlink">';
        $config['last_tag_close'] = '</span>';
         
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<span class="nextlink">';
        $config['next_tag_close'] = '</span>';

        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<span class="prevlink">';
        $config['prev_tag_close'] = '</span>';

        $config['cur_tag_open'] = '<span class="uk-text-bold">';
        $config['cur_tag_close'] = '</span>';

        $config['num_tag_open'] = '<span class="numlink">';
        $config['num_tag_close'] = '</span>';
         
        $this->my_pagination->initialize($config);
             
        return $this->my_pagination->create_links();
	}

	public function logout () {
		$this->session->unset_userdata('admincbros');
		redirect('CBROS/');
	}

}
