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

	public function lists($page=1) {
		// GET 방식의 검색 조건
		$criteria = array();
		
		// 상태
		if($this->input->get('status')) {
			$criteria['status'] = $this->input->get('status');
		}

		// 장비 종류
		if($this->input->get('type')) {
			$criteria['type'] = $this->input->get('type');
		}

		$num_rows = 15;
		$order_by = array('id' => 'desc');
		$offset = ($page - 1) * $num_rows;

		// 총 결과수
		$total_rows = $this->part_model->getRowCount($criteria);
		// 리스트 가져오기
		$rows = $this->part_model->getListBy($criteria, $order_by, $num_rows, $offset);

		// ===========
		// pagination
		// ===========
		$this->load->library('pagination');
		
		$config = array(
			'base_url' 		=> base_url() . 'admin/part/lists/',
			'prefix'		=> '',
			'total_rows'	=> $total_rows,
			'per_page'		=> $num_rows,
			'uri_segment'	=> 4,
			'num_links'		=> 5,
			'use_page_numbers'	=> TRUE
		);

		// 검색 조건이 있을 경우
		if(count($criteria)) {
			$config['suffix'] = '/?' . http_build_query($this->input->get());
			$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
		}

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['type'] = $this->input->get('type');

		$data['title']  = '관리자 > 장비 > 리스트';
		$data['current'] = 'page-admin-part';		
		$data['rows'] = $rows;
		
		$this->load->view('part_list', $data);
	}
	
	public function add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title']  = '관리자 > 장비 > 신규등록';
		$data['current'] = 'page-admin-part';		

		//================ refactoring needed ===================
		// 장비 카테고리 와 배열로 변경
		$em = $this->part_model->getEntityManager();

		// 카테고리 selectbox 생성
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getSubCategories(1);
		$cats = gs2_convert_for_dropdown($cats);
		$data['select_category'] = form_dropdown('category_id', $cats, 0, 'id="category_id" class="form-control"');

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

		// 납품처 selectbox 생성
		$data['select_company'] = form_dropdown('company_id', $companies, 0, 'id="company_id" class="form-control"');

		// 규칙 설정
		$this->form_validation->set_rules('type', '장비 타입', 'required');
		$this->form_validation->set_rules('name', '장비 모델명', 'required');
		$this->form_validation->set_rules('category_id', '장비 종류', 'required|greater_than[0]');
		$this->form_validation->set_rules('company_id', '납품처', 'required|greater_than[0]');

		if($this->form_validation->run() === FALSE){
			$this->load->view('part_add_form', $data);
		}
		else 
		{
			var_dump($_POST);

			// 새로운 장비 등록

			$part = new Entity\Part();
			$part->setName($this->input->post('name'));
			$part->setType($this->input->post('type'));
			$part->setManufacturer($this->input->post('manufacturer'));
			$part->setRegisterDate();
			$part->setStatus($this->input->post('status'));

			$category = $em->getReference('Entity\Category', $this->input->post('category_id'));
			$part->setCategory($category);

			$company = $em->getReference('Entity\Company', $this->input->post('company_id'));
			$part->setCompany($company);

			$this->part_model->_add($part);
			$this->part_model->_commit();		// 최종 반영

			// 입력 성공 메세지

			redirect('/admin/part');
		}
	}

	// 시리얼 장비 리스트
	public function serial() {
		$rows = $this->part_model->getSerialPartList();
		
		$data['title'] = '관리자 >> 시리얼장비 >> 목록';
		$data['rows'] =& $rows;

		$this->load->view('part_serial_list', $data);
	}

	public function serial_add() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['title'] = "관리자 >> 시리얼장비 >> 신규등록";

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

		// 재고 사무소 목록
		$arr_office = array();		
		$arr_office[0] = '-- 선택하세요 --';
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getMasterList();
		foreach($rows as $row) {
			$arr_office[$row->id] = $row->name;
		}

		// 시리얼 관리 장비 모델명
		$arr_part[0] = '-- 선택하세요 --';
		$rows = $this->part_model->getSerialPartModelList();
		foreach($rows as $row) {
			$arr_part[$row->id] = $row->name;
		}

		// selectbox 생성
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control"');
		$data['select_part'] = form_dropdown('part_id', $arr_part, 0, 'id="part_id" class="form-control"');

		// 규칙 설정
		$this->form_validation->set_rules('part_id', '장비모델', 'required|greater_than[0]');
		$this->form_validation->set_rules('office_id', '입고 사무소', 'required|greater_than[0]');
		$this->form_validation->set_rules('serial_number', '시리얼넘버', 'required');
		$this->form_validation->set_rules('date_enter', '최초 입고일', 'required');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('part_serial_add_form', $data);	
		} else {
			$post = $this->input->post();
			/* default */ 
			$post['is_valid'] = 'Y';									// 가용 여부
			$post['current_location'] = 'O@' . $post['office_id'];		// 입고 사무소
			$post['previous_location'] = '';
			$post['date_install']	= '';
			$post['qty'] = 1;

			// var_dump($post);
			// exit;

			$entry = $this->part_model->addSerialPart($post);
			if(!$entry) {
				die('에러');
			}

			// 재고량 변경
			$stock = $entry->part->getStock($this->input->post('office_id'));
			$stock->increase('new', 1);

			$this->part_model->_add($stock);
			$this->part_model->_commit();

			redirect('admin/part/serial');
		}

	}

	// 테스트
	public function ajax_serial_add() {

		print_r($_POST);
	}
}