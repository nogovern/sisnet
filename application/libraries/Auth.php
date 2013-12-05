<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Auth {
	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();

		$this->CI->load->library('session');
	}

	public function login($username, $password) {
		
		// user model load & get
		$this->CI->load->model('user_m', 'user_model');
		$user = $this->CI->user_model->getByUsername($username);

		if(!$user || $password != $user->password) {
			$this->CI->session->set_flashdata('error_message', '로그인 할 수 없습니다. 아이디 또는 비밀번호를 확인하세요');
			return FALSE;
		}

		// 성공시 
		return TRUE;
	}

	public function logout($ret_url = '') {
		;
	}

	public function isLoggedIn() {
		return FALSE;
	}

}