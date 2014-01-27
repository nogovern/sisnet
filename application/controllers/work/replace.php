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

	/////////////
	// 요청서 등록
	/////////////
	public function register() {
		$data['title'] = '교체 업무 >> 요청서 등록';
		$data['current'] = 'page-replace';

		$this->load->library('form_validation');
		$this->load->helper('form');

		// 사무소 select 생성
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = gs2_convert_for_dropdown($rows);
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control required"');

		// 규칙 설정		
		$this->form_validation->set_rules('office_id', '담당 사무소', 'required|greater_than[0]');
		$this->form_validation->set_rules('store_id', '교체 점포', 'required');
		$this->form_validation->set_rules('store_name', '교체 점포명', 'required');
		$this->form_validation->set_rules('date_open', '설치 요청일', 'required');
		$this->form_validation->set_rules('date_close', '철수 요청일', 'required');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('work/work_replace_register', $data);

		} else {
			echo 'good';
			gs2_dump($_POST);
		}
	}

	public function view($id) {
		echo '작업중';
	}

	public function update($id) {
		echo '작업중';
	}

}