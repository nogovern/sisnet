<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	이동 컨트롤러
*/
class Move extends CI_Controller
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
		$data['title'] = '이동 업무';
		$data['current'] = 'page-move';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getMoveList();
		
		$this->load->view('work/work_move_list', $data);
	}

	public function register() {
		echo '작업중';
	}

	public function view($id) {
		echo '작업중';
	}

	public function update($id) {
		echo '작업중';
	}

}