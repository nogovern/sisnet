<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	상태변경 컨트롤러
*/
class Change extends CI_Controller
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
		$data['title'] = '장비 상태변경 업무';
		$data['current'] = 'page-changer';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getChangeList();
		
		$this->load->view('work/work_change_list', $data);
	}

}