<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Office extends CI_Controller
{
	public function __construct() {
		parent::__construct();
		
		// load User model...
		$this->load->model('office_m', 'office_model');

	}

	public function index()
	{
		$this->lists();
	}

	public function lists($action = 'lists') {
		$items = $this->office_model->getList();

		$data = array(
			'rows' => $items,
			'title' => '사무소 리스트',
			'page_title' => '사무소 리스트'
			);

		$this->load->view('office_list', $data);
	}

	// 입고요청
	public function add() {
		$data['title'] = "사무소 신규 등록";

		$this->load->library('form_validation');
		$this->load->helper('form');

		//================ refactoring needed ===================
		$em = $this->office_model->getEntityManager();
		// 재고 사무소 목록
		$arr_office = array();		
		$arr_office[0] = '-- 선택하세요 --';
		$rows = $this->office_model->getMasterList();
		foreach($rows as $row) {
			$arr_office[$row->id] = $row->name;
		}

		// 담당자
		unset($rows);
		$rows = $em->getRepository('Entity\User')->findAll(); 
		$users = array();
		$users[0] = "-- 선택하세요 --";
		foreach($rows as $row) {
			$users[$row->id] = $row->name;
		}

		// selectbox 생성
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control"');
		$data['select_user'] = form_dropdown('user_id', $users, 0, 'id="user_id" class="form-control"');

		// 규칙 설정
		$this->form_validation->set_rules('name', '창고명칭', 'required');

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