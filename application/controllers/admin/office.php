<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Office extends CI_Controller
{
	public function __construct() {
		parent::__construct();
		
		// load User model...
		$this->load->model('office_m', 'office_model');

	}

	public function index($page=1)
	{
		$this->lists($page);
	}

	public function lists($page=1) {
		$data['page_title'] = '사무소 리스트';
		$data['title'] = '관리자 > 사무소 리스트';
		$data['currnet'] = 'page-admin-office';

		$data['rows'] = $this->office_model->getList();
		$this->load->view('office_list', $data);
	}

	// 입고요청
	public function add() {
		$data['title'] = "사무소 신규 등록";
		$data['currnet'] = 'page-admin-office';

		$this->load->library('form_validation');
		$this->load->helper('form');

		//================ refactoring needed ===================

		// master 사무소 dropdown
		$this->load->model('office_m', 'office_model');
		$arr_office = $this->office_model->convertForSelect($this->office_model->getMasterList());
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control"');

		// 직원 담당자 선택 dropdown
		$this->load->model('user_m', 'user_model');
		$arr_user = $this->user_model->convertForSelect($this->user_model->getListByType(1));
		$data['select_user'] = form_dropdown('user_id', $arr_user, 0, 'id="user_id" class="form-control"');

		// 규칙 설정
		$this->form_validation->set_rules('name', '사무소 이름', 'required');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('office_add_form', $data);

		} else {
			$post = $this->input->post();
			
			$this->office_model->add($post);
			// $this->office_model->_commit();

			redirect('admin/office');
			exit;
		}

	}
	
} // END class Office extends CI_Controller