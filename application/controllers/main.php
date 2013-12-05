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
		redirect('/stock');
	}

	public function admin() {
		echo "<h1>" . __method__ . "</h1>" ;
		echo anchor('/admin/user', '사용자관리페이지로 이동');
		echo "<br>[Todo] 로그인 기능 추가해야함";

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
				echo '로그인 성공! ' . $this->input->post('username') . ' 님 환영합니다.';
				exit;
			} else {
				echo '==== 로그인 실패!!! ======';
				echo '<pre>';
				var_dump($_POST);
				echo '</pre>';
			}
		} 
		
		$this->load->view('login', $data);
	}
}