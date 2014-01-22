<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	교체 컨트롤러
*/
class Replace extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists() {
		$data['title'] = '교체 업무';
		$data['current'] = 'page-replace';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getReplaceList();
		
		$this->load->view('work/work_replace_list', $data);
	}

	public function register() {
		echo '작업중';
	}

	public function view() {
		echo '작업중';
	}



}