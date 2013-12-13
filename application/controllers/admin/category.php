<?php 
/**
 * Category(장비종류) 컨트롤러
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		// load User model...
		$this->load->model('category_m', 'category_model');

		// 프로파일링 설정
		//$this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	public function add() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '카테고리 등록';

		// 규칙 설정
		$this->form_validation->set_rules('name', '카테고리명', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('category_add_form', $data);

		} else {
			$new = new Entity\Category();

			$new->code = $this->input->post('code');
			$new->date_register = new DateTime("now");		// 현재 시간
			$new->status = 'Y';

			// user_id 가 있으면 
			// $new->user = $user;

			redirect('/admin/category');
		}

	}

	public function lists() {

		$data['title'] = '점포 리스트';
		$data['current'] = 'page-admin-category';
		$data['rows'] = $this->category_model->getList();

		$this->load->view('category_list', $data);
	}

}