<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		// load User model...
		$this->load->model('user_m', 'user_model');
	}

	public function index($page=1) {
		$this->lists($page);
	}

	public function lists($page=1) {
		
		// GET 방식의 검색 조건
		$criteria = array();
		
		// 회원 상태
		if($this->input->get('status')) {
			$criteria['status'] = $this->input->get('status');
		}

		// 회원 타입
		if($this->input->get('type')) {
			$criteria['type'] = $this->input->get('type');
		}

		//============
		// pagination
		//============
		$this->load->library('pagination');

		$num_rows = 15;
		$base_url = base_url() . 'admin/user/lists/';

		$config = array(
			'base_url' 		=> $base_url,
			'prefix'		=> '',
			'per_page'		=> $num_rows,
			'uri_segment'	=> 4,
			'num_links'		=> 5,
			'use_page_numbers'	=> TRUE,
			'page_query_string'	=> FALSE
		);

		// 총 결과수
		$total_rows = $this->user_model->getRowCount($criteria);
		$config['total_rows'] = $total_rows;
		
		// 검색 조건이 있을 경우
		if(count($criteria)) {
			$config['suffix'] = '/?' . http_build_query($this->input->get());
			$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
		}

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// 목록 데이터
		$offset = ($page - 1) * $num_rows;
		$order_by = array('id' => 'desc');
		$rows = $this->user_model->getListBy($criteria, $order_by, $num_rows, $offset);
		$data['rows'] = $rows;
		
		$data['title'] = '관리자 > 사용자 > 리스트';
		$data['current'] = 'page-admin-user';

		$data['type'] = $this->input->get('type');		// 회원구분
		
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