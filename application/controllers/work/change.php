<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	상태변경 컨트롤러
*/
class Change extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		echo '<h1>공사중</h1>';
	}

}