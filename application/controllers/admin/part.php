<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Part extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->model('part_m', 'part_model');

		// 프로파일링 설정
		// $this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	public function lists($type = NULL) {
		if($type) {
			$items= $this->part_model->find(array('type' => $type));
		} else {
			$items = $this->part_model->getList();
		}

		$data['title']  = '장비리스트';		
		$data['rows'] = $items;
		
		$this->load->view('part_list.html', $data);
	}
	
	public function add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = "신규 장비를 등록하세요";

		// 규칙 설정
		$this->form_validation->set_rules('type', '장비 타입', 'required');
		$this->form_validation->set_rules('name', '장비 모델명', 'required');
		$this->form_validation->set_rules('category', '장비 종류', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('layout/header');
			$this->load->view('layout/navbar');
			$this->load->view('part_add_form', $data);
			$this->load->view('layout/footer');
		}
		else 
		{
			var_dump($_POST);

			// 새로운 사용자 등록

			$part = new Entity\Part();
			$part->setName($this->input->post('name'));
			$part->setType($this->input->post('type'));
			$part->setPartCode($this->input->post('category_name'));
			$part->setManufacturer($this->input->post('manufacturer'));
			$part->setRegisterDate();

			$this->part_model->save($part);

			// 입력 성공 메세지

			redirect('/admin/part');

		}
	}

	public function form() {

	}

	public function render( $view_url = '', $title = '기본 타이틀', $options)
	{

	}

	public function serial() {
		$rows = $this->part_model->getSerialPartList();
		
		$data['title'] = '---- 리스트 -------';
		$data['rows'] = $rows;

		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('part_serial_list.html', $data);
		$this->load->view('layout/footer');
	}
}