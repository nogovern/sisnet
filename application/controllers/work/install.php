<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 설치 업무 컨트롤러
*/
class Install extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$data['title'] = '설치 업무';
		$data['type'] = '';
		$data['rows'] = $this->work_model->getInstallList();


		$this->load->view('work_install_list', $data);
	}

	public function add() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '설치 업무';

		// 규칙 설정
		$this->form_validation->set_rules('store_name', '설치 점포', 'required');
		$this->form_validation->set_rules('date_open', '점포 개점일', 'required');
		$this->form_validation->set_rules('date_install', '설치 일시', 'required');
		$this->form_validation->set_rules('office_id', '설치 사무소', 'required|greater_than[0]');

		// 재고 사무소 목록
		$arr_office = array();		
		$arr_office[0] = '-- 선택하세요 --';
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		foreach($rows as $row) {
			$arr_office[$row->id] = $row->name;
		}

		// selectbox 생성
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control"');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('work_install_add_form', $data);
		} else {

		}

	}

	public function open() {
		echo '설치 업무를 시작합니다';
	}

	public function close() {
		echo '설치 업무를 종료합니다';
	}

}
