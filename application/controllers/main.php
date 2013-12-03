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
		$this->load->view('login');
	}
}