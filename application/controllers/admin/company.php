<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		// load User model...
		$this->load->model('company_m', 'company_model');

		// 프로파일링 설정
		//$this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	public function lists($page=1) {

		// GET 방식의 검색 조건
		$criteria = array();
		
		// 상태
		if($this->input->get('status')) {
			$criteria['status'] = $this->input->get('status');
		}

		// 거래처 종류
		if($this->input->get('type')) {
			$criteria['type'] = $this->input->get('type');
		}

		$num_rows = 15;
		$order_by = array('id' => 'desc');
		$offset = ($page - 1) * $num_rows;

		// 총 결과수
		$total_rows = $this->company_model->getRowCount($criteria);
		// 리스트 가져오기
		$rows = $this->company_model->getListBy($criteria, $order_by, $num_rows, $offset);

		// ===========
		// pagination
		// ===========
		$this->load->library('pagination');
		
		$config = array(
			'base_url' 		=> base_url() . 'admin/company/lists/',
			'prefix'		=> '',
			'total_rows'	=> $total_rows,
			'per_page'		=> $num_rows,
			'uri_segment'	=> 4,
			'num_links'		=> 5,
			'use_page_numbers'	=> TRUE
		);

		// 검색 조건이 있을 경우
		if(count($criteria)) {
			$config['suffix'] = '/?' . http_build_query($this->input->get());
			$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
		}

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$data['title'] = '업체 리스트';
		$data['current'] = 'page-admin-company';

		$data['type'] = $this->input->get('type');
		$data['rows'] = $rows;
		$this->load->view('company_list', $data);
	}

	// 등록
	public function add() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '거래처 등록';
		$data['current'] = 'page-admin-company';

		// 직원 담당자 선택 dropdown
		$this->load->model('user_m', 'user_model');
		$arr_user = gs2_convert_for_dropdown($this->user_model->getListByType(3));
		$data['select_user'] = form_dropdown('user_id', $arr_user, 0, 'id="user_id" class="form-control"');

		// 규칙 설정
		$this->form_validation->set_rules('type', '거래처 타입', 'required');
		$this->form_validation->set_rules('name', '거래처 업체명', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('company_add_form', $data);

		} else {
			// 바로 flush 한다
			$new_office = $this->company_model->create($this->input->post(), TRUE);

			redirect('/admin/company');
		}

	}

	/**
	 * view template method - view 페이지 url 을 받아 header, footer 를 붙여 출력
	 * @param  [type] $view_url
	 * @param  array  $data
	 * @return [type]
	 */
	public function render($view_url, $data = array())
	{
		$this->load->view('layout/header', array('title' => $data['title']));		
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}

}