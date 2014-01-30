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
		$this->load->helper('form');

		$data['title'] = '이동 업무';
		$data['current'] = 'page-move';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getMoveList();

		// 사무소 select 생성
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = gs2_convert_for_dropdown($rows);
		$data['select_sender'] = form_dropdown('send_office_id', $arr_office, $this->session->userdata('office_id'), 'id="send_office_id" class="form-control required"');
		$data['select_receiver'] = form_dropdown('receive_office_id', $arr_office, 0, 'id="receive_office_id" class="form-control required"');
		
		$this->load->view('work/work_move_list', $data);
	}

	public function register() {
		$post = $this->input->post();

		if(!count($post)) {
			echo '작업중';
		}

		gs2_dump($post);
	}

	public function view($id) {
		echo '작업중';
	}

	public function update($id) {
		echo '작업중';
	}

}