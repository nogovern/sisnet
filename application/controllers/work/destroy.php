<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	폐기 컨트롤러
*/
class Destroy extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists($page=1) {
		$this->load->helper('form');

		$data['title'] = '폐기 업무';
		$data['current'] = 'page-destroy';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getListBy(array('type' => '600'));

		// 사무소 select 생성
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = gs2_convert_for_dropdown($rows);
		$data['select_sender'] = form_dropdown('send_office_id', $arr_office, $this->session->userdata('office_id'), 'id="send_office_id" class="form-control required"');
		$data['select_receiver'] = form_dropdown('target_office_id', $arr_office, 0, 'id="target_office_id" class="form-control required"');
		
		$this->load->view('work/work_destroy_list', $data);
	}

	public function register() {
		$post_data = $this->input->post();


		if(!count($post_data)) {
			echo '작업중';
		} else {

			$post_data['op_type'] =  GS2_OP_TYPE_DESTROY;
			$post_data['office_id'] = $this->input->post('send_office_id');
			$post_data['date_request'] = date("Y-m-d");


			// 업무 생성
			$op = $this->work_model->createOperation( GS2_OP_TYPE_DESTROY, $post_data);

			// 로그 기록
			$log_data = array(
				'type'		=> '1',
				'content'	=> '폐기 업무가 생성되었음',
			);
			$this->work_model->addLog($op, $log_data, TRUE);

			redirect('work/destroy');
		}

	}

	public function view($id) {
		echo '작업중';
	}

	public function update($id) {
		echo '작업중';
	}

}