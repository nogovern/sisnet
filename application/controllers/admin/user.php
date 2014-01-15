<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		// load User model...
		$this->load->model('user_m', 'user_model');
	}

	public function index($type='', $page=1) {
		$this->lists($type, $page);
	}

	public function lists($type='', $page=1) {
		if(!$type) {
			$rows = $this->user_model->getList();
		} else {
			$rows = $this->user_model->getListByType($type);
			$config['suffix'] = '&type=' . $type;
		}

		// pagination
		// ===========
		// user/lists/?page=1&type=2 식으로 사용하려면 config에 
		// prefix, suffix 를 설정해야 함
		// (소스에 보면) $this->prefix.$n.$this->suffix;
		$this->load->library('pagination');
		$config = array(
			'base_url' 		=> base_url() . 'admin/user/lists/',
			'prefix'		=> '?page=',
			'total_rows'	=> count($rows),
			'per_page'		=> 10,
			'use_page_numbers'	=> TRUE,
			'page_query_string'	=> FALSE
		);

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$data['title'] = '관리자 > 사용자 > 리스트';
		$data['current'] = 'page-admin-user';

		$data['rows'] = $rows;
		$data['type'] = $type;		// 회원구분
		
		$this->load->view('user_list', $data);
	}

	public function view($id) {
		$user = $this->user_model->get($id);

		$data['title'] = "사용자 정보";
		$data['current'] = 'page-admin-user';

		echo ' 제 이름은 ' . $user->name;
	}
	
	public function add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = "사용자 >> 사용자 등록";
		$data['current'] = 'page-user';

		$em = $this->user_model->getEntityManager();
		// 사무소 목록
		$offices = $em->getRepository('Entity\Office')->findAll();
		$option_offices = array();
		$option_offices[0] = "-- 사무소 를 선택하세요 --";
		foreach($offices as $office) {
			$option_offices[$office->id] = $office->name;
		}

		// 거래처 목록
		$companies = $em->getRepository('Entity\Company')->findBy(array('type' => 3));
		$option_companies = array();
		$option_companies[0] = "-- 업체 를 선택하세요 --";
		foreach($companies as $company) {
			$option_companies[$company->id] = $company->name;
		}

		// selectbox 생성
		$data['form_office_select'] = form_dropdown('office_id', $option_offices, 0, 'id="office_id" class="form-control"');
		$data['form_company_select'] = form_dropdown('company_id', $option_companies, 0, 'id="company_id" class="form-control"');

		// 규칙 설정
		$this->form_validation->set_rules('username', '사용자 ID', 'required');
		$this->form_validation->set_rules('name', '이름', 'required');
		$this->form_validation->set_rules('type', '사용자 종류', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('user_add_form', $data);
		}
		else 
		{
			// 새로운 사용자 등록
			$user = new Entity\User();

			$user->setUsername($this->input->post('username'));
			$user->setName($this->input->post('name'));
			$user->setPassword($this->input->post('password'));
			$user->setType($this->input->post('type'));
			$user->setPhone($this->input->post('phone'));
			$user->setEmail($this->input->post('email'));
			$user->setDateRegister();
			$user->setStatus(1);

			// 사무소 유저일 경우
			if($this->input->post('office_id')) {
				$user->setOffice($em->getReference('Entity\Office', $this->input->post('office_id')));
			}

			// 거래처 유저일 경우
			if($this->input->post('company_id')) {
				$user->setCompany($em->getReference('Entity\Company', $this->input->post('company_id')));
			}

			// 저장
			$this->user_model->_add($user);
			$this->user_model->_commit();

			// 입력 성공 메세지
			var_dump($_POST);
			echo "성공";

			redirect('/admin/user');

		}
	}

	public function form() {
		$data['disable_navbar']	= TRUE;
		$data['disable_footer']	= TRUE;
		
		// $this->load->view('layout/header', $data);		
		// $this->load->view('user_add_form', $data);
		// $this->load->view('layout/footer');
		
		$this->render('user_add_form', '관리자 >> 사용자 등록', $data);
	}

	public function render( $view_url = '', $title = '기본 타이틀', $options)
	{
		$data = array();

		$data['title'] = $title;

		if( is_array($options) && count($options)) {
			foreach( $options as $k => $v) {
				$data[$k] = $v;
			}
		}
		$this->load->view('layout/header', $data);
		if( !isset($options['disable_navbar']) || $options['disable_navbar'] == FALSE) {
			$this->load->view('layout/navbar');
		} 
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}

}