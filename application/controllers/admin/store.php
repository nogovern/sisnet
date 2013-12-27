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
		$this->form_validation->set_rules('code', '점포코드', 'required');
		$this->form_validation->set_rules('name', '점포명', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('store_add_form', $data);

		} else {
			var_dump($_POST);
			
			$store = new Entity\Store();

			$store->code 		= $this->input->post('code');
			$store->name 		= $this->input->post('name');
			$store->owner_name 	= $this->input->post('ower_name');
			$store->tel 		= $this->input->post('tel');
			$store->address 	= $this->input->post('address');
			$store->tel_rfc 	= $this->input->post('tel_rfc');
			$store->tel_ofc 	= $this->input->post('tel_ofc');
			$store->scale 		= $this->input->post('scale');
			$store->join_type 	= $this->input->post('join_type');
			$store->has_postbox = $this->input->post('has_postbox');
			$store->status 		= $this->input->post('status');
			$store->date_register = new DateTime("now");		// 현재 시간

			$this->store_model->_add($store);
			$this->store_model->_commit();

			// exit;

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