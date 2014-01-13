<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	work -> ajax 공통 컨트롤러
*/
class Ajax extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		// operation.id 가 post 로 넘어오는지 검사!
		$id = $this->input->post('id');
		if(!$id) {
			die('잘못된 접근입니다.');
		}

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		echo '워크 메인';
	}

	// 요청 확정
	public function accept_request() {
		$id = $this->input->post('id');

		$post = array(
			'office_id'	=> $this->input->post('office_id'),
			'worker_id'	=> $this->input->post('worker_id'),
			'date_work'	=> $this->input->post('date_work'),
			'memo'		=> $this->input->post('memo')
			);
		
		$op = $this->work_model->acceptRequest($id, $post);
		echo 'success';
	}

	public function add_item() {

	}

	public function remove_item() {

	}

	public function write_memo() {
		$id = $this->input->post('id');
		
		$post = array(
			'user_id'	=> $this->session->userdata('user_id'),			// 로그인 한 유저
			'content'	=> $this->input->post('memo'),
			'type'		=> '2',
		);

		$this->work_model->addLog($id, $post, TRUE);

		echo '메모를 저장하였습니다';
	}

	public function store_complete() {


	}

	public function complete() {

	}

	// 승인 
	public function approve() {

	}
	
}