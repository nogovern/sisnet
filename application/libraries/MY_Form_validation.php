<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 폼검증 라이브러리 확장
*/
class MY_Form_validation extends CI_Form_validation
{
	
	function __construct($rules = array())
	{
		parent::__construct($rules);
		log_message('debug', '*** Hello from MY_Form_validation ***');
	}

	public function callback_unique_name() {
		return FALSE;
	}

	public function debug() {
		echo '왜 불러!';
	}
		
}