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
		$this->form_validation->set_rules('name', '점포명', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('store_add_form', $data);

		} else {
			var_dump($_POST);
			
			$store = new Entity\Store();

			$store->code 		= $this->input->post('code');
			$store->code2 		= $this->input->post('code2');
			$store->name 		= $this->input->post('name');
			$store->owner_name 	= $this->input->post('owner_name');
			$store->owner_tel 	= $this->input->post('owner_tel');
			$store->tel 		= $this->input->post('tel');
			$store->address 	= $this->input->post('address');
			$store->rfc_name 	= $this->input->post('rfc_name');
			$store->rft_tel 	= $this->input->post('rft_tel');
			$store->ofc_name 	= $this->input->post('ofc_name');
			$store->ofc_tel 	= $this->input->post('ofc_tel');
			$store->join_type 	= $this->input->post('join_type');
			$store->has_postbox = $this->input->post('has_postbox');
			$store->status 		= $this->input->post('status');
			$store->setDateRegister();

			$this->store_model->_add($store);
			$this->store_model->_commit();

			// exit;
			redirect('/admin/store');
		}
	}

	public function register($mode=NULL){
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '거래처 등록';

		// 규칙 설정
		$this->form_validation->set_rules('name', '점포명', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('store_add_popup_form', $data);

		} else {
			var_dump($_POST);
			
			$store = new Entity\Store();

			$store->code 		= $this->input->post('code');
			$store->code2 		= $this->input->post('code2');
			$store->name 		= $this->input->post('name');
			$store->owner_name 	= $this->input->post('owner_name');
			$store->owner_tel 	= $this->input->post('owner_tel');
			$store->tel 		= $this->input->post('tel');
			$store->address 	= $this->input->post('address');
			$store->rfc_name 	= $this->input->post('rfc_name');
			$store->rft_tel 	= $this->input->post('rft_tel');
			$store->ofc_name 	= $this->input->post('ofc_name');
			$store->ofc_tel 	= $this->input->post('ofc_tel');
			$store->join_type 	= $this->input->post('join_type');
			$store->has_postbox = $this->input->post('has_postbox');
			$store->status 		= $this->input->post('status');
			$store->setDateRegister();

			$this->store_model->_add($store);
			$this->store_model->_commit();

			// exit;
			redirect('/admin/store');
		}
	}

	public function edit($id) {
		if(empty($id)){
			alert("올바르지 않은 접근입니다");
			exit;
		}


	}

	public function lists() {

		$data['title'] = '점포 리스트';
		$data['current'] = 'page-admin-store';
		$data['rows'] = $this->store_model->getList();

		$this->load->view('store_list', $data);
	}

}