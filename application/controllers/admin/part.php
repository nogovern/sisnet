<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Part extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		
		// $this->load->model('user_repository');
		$this->load->library('doctrine');

		// 프로파일링 설정
		// $this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	public function lists() {
		$em = $this->doctrine->em;

		$items = $em->getRepository('Entity\Part')->findAll();
		$data['rows'] = $items;
		
		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('part_list.html', $data);
		$this->load->view('layout/footer');
	}
	
	public function add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = "신규 장비를 등록하세요";

		// 에러 구분자 UI 설정
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '<button type="button" class="close" aria-hidden="true">&times;</button></div>');

		// 규칙 설정
		$this->form_validation->set_rules('type', '장비 타입', 'required');
		$this->form_validation->set_rules('name', '장비명', 'required');
		$this->form_validation->set_rules('qty_total', '사용자 ID', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('layout/header');
			$this->load->view('layout/navbar');
			$this->load->view('part_add_form', $data);
			$this->load->view('layout/footer');
		}
		else 
		{
			// 새로운 사용자 등록
			$em = $this->doctrine->em;

			$user = new Entity\User();


			// $em->persist($user);
			// $em->flush();

			// 입력 성공 메세지
			var_dump($_POST);
			echo "성공";

			redirect('/admin/user');

		}
	}

	public function form() {

	}

	public function render( $view_url = '', $title = '기본 타이틀', $options)
	{

	}
}