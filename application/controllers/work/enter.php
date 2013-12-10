<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 입고 컨트롤러
*/
class Enter extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
		$this->em = $this->work_model->getEntityManager();

		$this->load->library('auth');
	}

	public function index() {
		$this->main();
	}

	public function main() {

		$data['title'] = '입고업무';
		$data['type'] = '';
		$data['rows'] = $this->work_model->getEnterList();
		
		$this->load->view('work_enter_list', $data);
	}

	// 입고요청
	public function add() {
		$data['title'] = "입고 요청 작성";

		$this->load->library('form_validation');
		$this->load->helper('form');

		$this->load->view('work_enter_add_form', $data);
	}

	public function request() {
		$this->add();
	}

	// 납품처 기능 
	public function check_request() {
		;
	}

	// 
	public function _remap($method, $params = array()) {
		if(!$method) 
			$method = 'main'; 
		
		if(method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}

		show_404();
	}
}
