<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Auth {
	protected $CI;

	public function __construct() {
		$this->CI =& get_instance();

		// 세션 자동 실행 됨
		// $this->CI->load->library('session');

		// user model load & get
		$this->CI->load->model('user_m', 'user_model');
	}

	public function login($username, $password) {
		
		// 사용 편하게
		$session = $this->CI->session;
		$model = $this->CI->user_model;
		$em = $this->CI->doctrine->em;

		$user = $model->getByUsername($username);

		if(!$user || $password != $user->password) {
			$session->set_flashdata('error_message', '로그인 할 수 없습니다. 아이디 또는 비밀번호를 확인하세요');
			return FALSE;
		}

		////////////
		// 성공   //
		////////////

		$session->set_userdata('user_id', $user->id);
		$session->set_userdata('username', $username);			// 회원 ID
		$session->set_userdata('user_name', $user->name);
		$session->set_userdata('user_type', $user->type);
		$session->set_userdata('user_belongto', '시스네트');

		// 소속 사무소 or 소속 거래처
		$session->set_userdata('office_id', ($user->office) ? $user->office->id : NULL);
		$session->set_userdata('office_name', ($user->office) ? $user->office->name : NULL);
		
		// 담당 사무소 or 담당 거래처
		$session->set_userdata('company_id', ($user->company) ? $user->company->id : NULL);
		$session->set_userdata('company_name', ($user->company) ? $user->company->name : NULL);

		///////////////////
		/// 접속 로그 기록
		////////////////////

		return TRUE;
	}

	public function logout($ret_url = '') {
		$this->CI->session->sess_destroy();

		echo '로그아웃 되었습니다';
	}

	/**
	 * 로그인 상태인지 아닌지??
	 * @return boolean TRUE or FALSE
	 */
	public function isLoggedIn() {
		return ( $this->CI->session->userdata('username'))  ? TRUE : FALSE;
	}

}