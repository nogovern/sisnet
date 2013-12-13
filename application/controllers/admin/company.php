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

	public function add() {
		$this->load->library('Form_Validation');
		$this->load->helper('form');

		$data['title'] = '거래처 등록';

		// 규칙 설정
		$this->form_validation->set_rules('type', '거래처 타입', 'required');
		$this->form_validation->set_rules('name', '거래처 업체명', 'required');
		$this->form_validation->set_rules('code', '거래처 코드', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('layout/header');
			$this->load->view('layout/navbar');
			$this->load->view('company_add_form', $data);
			$this->load->view('layout/footer');

		} else {
			$new = new Entity\Company();

			$new->code = $this->input->post('code');
			$new->type = $this->input->post('type');
			$new->name = $this->input->post('name');
			$new->tel = $this->input->post('tel');
			$new->address = $this->input->post('address');
			$new->memo = $this->input->post('memo');
			$new->date_register = new DateTime("now");		// 현재 시간
			$new->status = 'Y';

			// user_id 가 있으면 
			// $new->user = $user;

			$this->company_model->save($new);

			redirect('/admin/company');
		}

	}

	public function lists() {

		$data['page_title'] = '업체 리스트';
		$data['rows'] = $this->company_model->getList();

		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('company_list', $data);
		$this->load->view('layout/footer');
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