<?php
/**
 * 로그인 여부를 확인한다
 */
class Acl {

	public function __construct() {
		// echo "login hook is loaded... <br> ";
	}

	// 로그인 체크
	function checkLogin() {
		// 로그인 체크 안하는 1st uri segment 배열
		$allowed_controller = array("main", "tests", "login");

		$CI =& get_instance();

		$method = $CI->uri->segment(1);
		// 첫페이지 및 특정페이지에서는 로그인 체크를 안함
		if(count($CI->uri->segments) < 1 || in_array($method, $allowed_controller)) {
			return true;
		} 

		// 로그인 확인
		$CI->load->helper('alert');
		if(!$CI->auth->isLoggedIn()) {
			alert('로그인 하셔야 합니다', site_url('/'));
		}
	}
}