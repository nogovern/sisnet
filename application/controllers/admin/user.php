<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
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

		$items = $em->getRepository('Entity\User')->findAll();
		$data['rows'] = $items;
		
		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('user_list.html', $data);
		$this->load->view('layout/footer');
	}
	
	public function add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = "Create a new user";

		// 에러 구분자 UI 설정
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '<button type="button" class="close" aria-hidden="true">&times;</button></div>');

		// 규칙 설정
		$this->form_validation->set_rules('username', '사용자 ID', 'required');
		$this->form_validation->set_rules('name', '이름', 'required');
		$this->form_validation->set_rules('type', '사용자 종류', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('layout/header');
			$this->load->view('layout/navbar');
			$this->load->view('user_add_form', $data);
			$this->load->view('layout/footer');
		}
		else 
		{
			// 새로운 사용자 등록
			$em = $this->doctrine->em;

			$user = new Entity\User();

			$user->setUsername($this->input->post('username'));
			$user->setName($this->input->post('name'));
			$user->setPassword($this->input->post('password'));
			$user->setType($this->input->post('type'));
			$user->setDateRegister();
			$user->setStatus(1);

			$em->persist($user);
			$em->flush();

			// 입력 성공 메세지
			var_dump($_POST);
			echo "성공";

			redirect('/admin/user');

		}
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