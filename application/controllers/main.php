<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	메인 컨트롤러
*/
class Main extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		// $this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->login();
	}

	public function admin() {
		echo "<h1>" . __method__ . "</h1>" ;
	}

	/////////////////
	// 로그인 화면 //
	/////////////////
	public function login() {
		// 로그인 되어 있으면 페이지 이동  
		if($this->auth->isLoggedIn()) {
			if($this->session->userdata('user_level') == GS2_USER_LEVEL_COMPANY) {
				redirect('stock');
			} else {
				redirect('schedule');
			}
		}

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'username', 'required');

		///// data //////
		$data = array();

		if($this->form_validation->run() === TRUE) {
			if($this->auth->login($this->input->post('username'), $this->input->post('password'))) {
				// $this->load->helper('alert');

				// 로그인 메세지 후 기본페이지 이동
				if($this->session->userdata('user_level') == GS2_USER_LEVEL_COMPANY) {
					alert('로그인 되었습니다', site_url('stock'));
				} else {
					alert('로그인 되었습니다', site_url('schedule'));
				}

			} else {
				alert('로그인에 실패하였습니다.\n정보 확인 후 다시 시도해 주세요.', site_url('login'));
			}
		} 
		
		$this->load->view('login', $data);
	}

	/////////////////
	// 로그아웃
	/////////////////
	public function logout() {
		// $this->load->library('auth');			// 자동 로드됨
		// $this->load->helper('alert');

		if($this->auth->isLoggedIn()) {
			$this->auth->logout();
			alert('성공적으로 로그아웃 되었습니다', site_url('/'));
		} else {
			alert('로그인 먼저 하셔야 합니다');
		}

		// redirect('login');
	}
}