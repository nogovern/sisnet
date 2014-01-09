<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 철수 컨트롤러
*/
class Close extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		// 로그인 확인
		if(!$this->auth->isLoggedIn()) {
			$this->load->helper('alert');
			alert('로그인 하셔야 합니다', site_url('/'));
		}

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists() {
		$data['title'] = '철수업무';
		$data['type'] = '';
		$data['rows'] = $this->work_model->getCloseList();
		
		$this->load->view('work_close_list', $data);
	}

	// 철수 요청
	public function request() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '철수업무';
		$data['user_id'] = $this->session->userdata('user_id');

		// 규칙 설정
		$this->form_validation->set_rules('office_id', '설치 사무소', 'required|greater_than[0]');
		$this->form_validation->set_rules('store_name', '설치 점포', 'required');
		$this->form_validation->set_rules('date_work', '점포 폐점일', 'required');
		$this->form_validation->set_rules('date_request', '설치 일시', 'required');

		// 담당 사무소 목록
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = $this->office_model->convertForSelect($rows);

		// selectbox 생성
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control required"');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('work_close_add_form', $data);
		} else {
			echo '철수 요청 가상 등록. but...';
		}
	}

	// 철수 요청
	public function add() {
		$this->request();
	}

	/**
	 * 철수 업무 상세보기
	 * 
	 * @param  integer $id 		업무 ID (operation.id )
	 * @return void
	 */
	public function view($id) {
		$data['title'] = "철수 업무 상세 보기";
		$this->load->view('work_close_view', $data);
	}

}
