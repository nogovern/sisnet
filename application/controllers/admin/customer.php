<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		// load User model...
		$this->load->model('customer_m', 'customer_model');

		// 프로파일링 설정
		//$this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	public function add() {
		echo '업체 등록 화면.. coming soon';
	}

	public function lists() {

		$data['page_title'] = '업체 리스트';
		$data['rows'] = $this->customer_model->getList();

		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('customer_list.html', $data);
		$this->load->view('layout/footer');
	}

	/**
	 * view template method - view 페이지 url 을 받아 header, footer 를 붙여 출력
	 * @param  [type] $view_url
	 * @param  array  $data
	 * @return [type]
	 */
	public function render($view_url, $data = array())
	{
		$this->load->view('layout/header', array('title' => $data['title']));		
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}

}