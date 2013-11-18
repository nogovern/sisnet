<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 사용자 컨트롤러
 */

class Users extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->model('user_repository');
	}

	public function index() {
		$this->lists();
	}

	public function lists() {
		$data['rows'] = $this->user_repository->lists();
		
		$this->load->view('layout/header');		
		$this->load->view('user_list.html', $data);
		$this->load->view('layout/footer');
	}

	public function auto_add() {
		$data = array(
			"name" => "테스트1",	
			"username" => "테스트1",	
			"password" => "********",	
			"gubun" => "1",	
			"phone" => "",	
			"email" => "",	
			"fax" => "",	
			"date_register" => "",	
			"status" => "1"
		);	

		$this->user_repository->add($data);
	}

	public function form() {
		$data = array();

		$this->load->view('layout/header');		
		$this->load->view('user_add_form', $data);
		$this->load->view('layout/footer');
	}
}