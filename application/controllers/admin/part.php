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

		//================ refactoring needed ===================
		// 장비 카테고리 와 배열로 변경
		$em = $this->part_model->getEntityManager();

		$parent = $em->getRepository('Entity\Category')->find(1);
		$rows = $em
					->getRepository('Entity\Category')
					->findBy(
						array('parent' => $parent),
						array('id' => 'ASC')				// order by 
					);
		$cats[0] = "--선택하세요--";
		foreach($rows as $row) {
			$cats[$row->id] = $row->name; 
		}

		// 납품처 목록
		unset($rows);
		$rows = $em->getRepository('Entity\Company')->findBy(
				array('type' => 3),
				array('id' => 'ASC')
			);
		$companies[0] = '--선택하세요';
		foreach($rows as $row) {
			$companies[$row->id] = $row->name;
		}

		// selectbox 생성
		$data['select_category'] = form_dropdown('category_id', $cats, 0, 'id="category_id" class="form-control"');
		$data['select_company'] = form_dropdown('company_id', $companies, 0, 'id="company_id" class="form-control"');

		// 규칙 설정
		$this->form_validation->set_rules('type', '장비 타입', 'required');
		$this->form_validation->set_rules('name', '장비 모델명', 'required');
		$this->form_validation->set_rules('category_id', '장비 종류', 'required');
		$this->form_validation->set_rules('company_id', '납품처', 'required');

		if($this->form_validation->run() === FALSE){
			$this->load->view('part_add_form', $data);
		}
		else 
		{
			var_dump($_POST);

			// 새로운 사용자 등록

			$part = new Entity\Part();
			$part->setName($this->input->post('name'));
			$part->setType($this->input->post('type'));
			$part->setManufacturer($this->input->post('manufacturer'));
			$part->setRegisterDate();

			$category = $em->getReference('Entity\Category', $this->input->post('category_id'));
			$part->setCategory($category);

			$company = $em->getReference('Entity\Company', $this->input->post('company_id'));
			$part->setCompany($company);

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
		$this->load->view('part_serial_list', $data);
		$this->load->view('layout/footer');
	}
}