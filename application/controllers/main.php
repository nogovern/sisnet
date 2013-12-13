<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	메인 컨트롤러
*/
class Main extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$argv = func_get_args();
	}

	public function index() {
		$this->login();
	}

	public function admin() {
		echo "<h1>" . __method__ . "</h1>" ;
	}

	// 로그인 화면
	public function login() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		// auth liabrary
		$this->load->library('auth');

		$this->form_validation->set_rules('username', 'username', 'required');

		///// data //////
		$data = array();

		if($this->form_validation->run() === TRUE) {
			if($this->auth->login($this->input->post('username'), $this->input->post('password'))) {
				$this->load->helper('alert');

				// 로그인 메세지
				alert('로그인 되었습니다', site_url('stock'));
			} else {
				echo '==== 로그인 실패!!! ======';
				echo '<pre>';
				var_dump($_POST);
				echo '</pre>';
			}
		} 
		
		$this->load->view('login', $data);
	}

	// 로그아웃
	public function logout() {
		$this->load->library('auth');
		$this->load->helper('alert');

		if($this->auth->isLoggedIn()) {
			$this->auth->logout();
			alert('성공적으로 로그아웃 되었습니다', site_url('/'));
		} else {
			alert('로그인 먼저 해라!');
		}

	}
}