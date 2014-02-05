<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 재고 컨트롤러
*/
class Stock extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('stock_m', 'stock_model');

		// 로그인 확인
		if(!$this->auth->isLoggedIn()) {
			$this->load->helper('alert');
			alert('로그인 하셔야 합니다', site_url('/'));
		}
	}

	public function index() {
		$this->lists();
	}

	// 전체 장비 재고 리스트
	public function lists($page=1) {

		$office_id = $this->input->get('office_id');
		if(!$office_id) {
			$office_id = $this->session->userdata('office_id');		// 유저 소속 사무소
			
			// 세션에 office_id 가 없는 경우
			if(!$office_id) {
				$office_id = 'all';
			}
		}
		
		$data['title'] = '전체 재고 현황';
		$data['current'] = 'page-stock';
		$data['this_office'] = $office_id;

		// 검색 조건
		$criteria = array();

		//============
		// pagination
		//============
		$this->load->library('pagination');

		$num_rows = 15;
		$base_url = base_url() . 'stock/lists/';

		$config = array(
			'base_url' 		=> $base_url,
			'prefix'		=> '',
			'per_page'		=> $num_rows,
			'uri_segment'	=> 3,
			'num_links'		=> 5,
			'use_page_numbers'	=> TRUE,
			'page_query_string'	=> FALSE
		);
		
		$this->load->model('office_m', 'office_model');
		$data['office_list'] = $this->office_model->getList();
		
		// 전체 재고		
		if($office_id == 'all') {
			// $em = $this->stock_model->getEntityManager();
			// $data['rows'] = $em->getRepository('Entity\Part')->findAll();		// part Entity

			$this->load->model('part_m', 'part_model');
			// 총 결과수
			$total_rows = $this->part_model->getRowCount($criteria);
			$config['total_rows'] = $total_rows;
			
			// 검색 조건이 있을 경우
			if($this->input->get()) {
				$config['suffix'] = '/?' . http_build_query($this->input->get());
				$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
			}

			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();

			// 목록 데이터
			$offset = ($page - 1) * $num_rows;
			$order_by = array('id' => 'desc');
			
			$rows = $this->part_model->getListBy($criteria, $order_by, $num_rows, $offset);
			$data['rows'] = $rows;

			$this->load->view('stock_list', $data);
		} 
		// 사무소별 장비 재고
		else {
			$criteria['office'] = $this->office_model->get($office_id);

			// 총 결과수
			$total_rows = $this->stock_model->getRowCount($criteria);
			$config['total_rows'] = $total_rows;
			
			// 검색 조건이 있을 경우
			if($this->input->get()) {
				$config['suffix'] = '/?' . http_build_query($this->input->get());
				$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
			}

			$this->pagination->initialize($config);
			$data['pagination'] = $this->pagination->create_links();

			// 목록 데이터
			$offset = ($page - 1) * $num_rows;
			$order_by = array('id' => 'desc');
			
			$rows = $this->stock_model->getListBy($criteria, $order_by, $num_rows, $offset);
			$data['rows'] = $rows;
			$data['total_rows'] = $total_rows;

			// $office = $this->office_model->get($office_id);
			// $rows = $office->getStockList();				// stock Entity

			// $data['rows'] = $rows;

			$this->load->view('stock_list_by_office', $data);
		}

	}

	public function add() {
		//////////
		// 폼  //
		//////////
		$this->load->helper('form');
		$this->load->library('form_validation');

		$em = $this->stock_model->getEntityManager();
		// 장비 목록
		$parts = $em->getRepository('Entity\Part')->findAll();
		$option_parts = array();
		$option_parts[0] = "-- 장비를 선택하세요 --";
		foreach($parts as $part) {
			$option_parts[$part->id] = $part->name;
		}

		// 사무소 목록
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList(); 
		$arr_office = array();
		$arr_office[0] = "-- 재고 사무소를 선택하세요 --";
		foreach($rows as $row) {
			$arr_office[$row->id] = $row->name;
		}


		$data['title'] = '재고 수동 입력';
		$data['select_part'] = form_dropdown('part_id', $option_parts, 0, 'id="part_id" class="form-control"');
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control"');

        // 에러 구분자 UI 설정
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '<button type="button" class="close" aria-hidden="true">&times;</button></div>');

		// 규칙 설정
		$this->form_validation->set_rules('part_id', '장비 모델명', 'required|greater_than');
		$this->form_validation->set_rules('office_id', '창고', 'required|greater_than');
		$this->form_validation->set_rules('qty_minumum', '기준수량', 'numeric');
		$this->form_validation->set_rules('qty_new', '신품 수량', 'numeric');
		$this->form_validation->set_rules('qty_used', '중고 수량', 'numeric');

		$this->form_validation->set_message('greater_than', '%s 필드는 필수 항목 입니다');

		if($this->form_validation->run() === FALSE){
			$this->load->view('layout/header');
			$this->load->view('layout/navbar');
			$this->load->view('stock_manual_add_form', $data);
			$this->load->view('layout/footer');
		} 
		else 
		{
			var_dump($_POST);

			// -- 라이브러리 화 해야 하는데 어디에 하지????
			// 재고가 등록되어 있는지 여부 확인
			$stock = $em->getRepository('Entity\Stock')->findBy(array(
				'part' => (int)$this->input->post('part_id'),
				'office' => (int)$this->input->post('office_id')
			));

			$has_stock = (count($stock)) ? TRUE : FALSE;

			// 재고 등록되어 있지 않으면 등록
			// 수량이 모두 0이면 등록 하지 않음
			if($has_stock === FALSE) {
				$part = $em->getReference('Entity\Part', (int)$this->input->post('part_id'));
				$office = $em->getReference('Entity\office', (int)$this->input->post('office_id'));

				$stock_arr = array(
					'part'		=> $part,
					'office'	=> $office,
					'minimum'	=> $this->input->post('qty_minimum'),
					'new'		=> (int)$this->input->post('qty_new'),
					'used' 		=> $this->input->post('qty_new'),
				);

				$this->load->model('part_m', 'part_model');
				$stock = $this->part_model->createStock($stock_arr, TRUE);

				unset($stock);

				redirect('/stock');
			} else {
				alert("재고 등록되어 있으면 안됨", '/stock');
			}
		}

	}
}