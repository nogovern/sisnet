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
		$this->load->view('layout/navbar');
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

	public function add() {
		$data = array();

		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('user_add_form', $data);
		$this->load->view('layout/footer');
	}

	public function form() {
		$data['disable_navbar']	= TRUE;
		$data['disable_footer']	= TRUE;
		
		// $this->load->view('layout/header', $data);		
		// $this->load->view('user_add_form', $data);
		// $this->load->view('layout/footer');
		
		$this->render('user_add_form', '관리자 >> 사용자 등록', $data);
	}

	public function render( $view_url = '', $title = '기본 타이틀', $options)
	{
		$data = array();

		$data['title'] = $title;

		if( is_array($options) && count($options)) {
			foreach( $options as $k => $v) {
				$data[$k] = $v;
			}
		}
		$this->load->view('layout/header', $data);
		if( !isset($options['disable_navbar']) || $options['disable_navbar'] == FALSE) {
			$this->load->view('layout/navbar');
		} 
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}
}