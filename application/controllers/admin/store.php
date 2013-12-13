<?php 
/**
 * Store 컨트롤러
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		// load User model...
		$this->load->model('store_m', 'store_model');

		// 프로파일링 설정
		//$this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	public function add() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '거래처 등록';

		// 규칙 설정
		$this->form_validation->set_rules('type', '거래처 타입', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('store_add_form', $data);

		} else {
			$new = new Entity\Store();

			$new->code = $this->input->post('code');
			$new->date_register = new DateTime("now");		// 현재 시간
			$new->status = 'Y';

			// user_id 가 있으면 
			// $new->user = $user;

			redirect('/admin/store');
		}

	}

	public function lists() {

		$data['title'] = '점포 리스트';
		$data['current'] = 'page-admin-store';
		$data['rows'] = $this->store_model->getList();

		$this->load->view('store_list', $data);
	}

}