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
		echo __method__ ;
	}

	public function admin() {
		echo __method__ ;
	}
}