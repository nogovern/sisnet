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

	public function lists() {

		$data['page_title'] = '업체 리스트';
		$data['rows'] = $this->company_model->getList();

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
		$arr_user = $this->user_model->convertForSelect($this->user_model->getListByType(3));
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