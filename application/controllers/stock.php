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

		$office_id = $this->input->get('off_id');

		if($office_id === false) {
			$office_id = $this->session->userdata('office_id');		// 유저 소속 사무소
			
			// 세션에 office_id 가 없는 경우
			if(!$office_id) {
				$office_id = '0';
			}
		} 
		
		$data['title'] = '재고 현황 - 전체';
		$data['current'] = 'page-stock';

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

		// 목록 데이터
		$offset = ($page - 1) * $num_rows;
		$order_by = array('id' => 'desc');
		//---------------

		///////////////
		// 검색 조건
		///////////////
		$criteria = array();

		// 사무소
		if($office_id > 0) {
			$criteria['office'] = $office_id;
		}
		$criteria['category'] = $this->input->get('cat_id');	// 장비 종류
		$criteria['part'] = $this->input->get('part_id');		// 장비 모델
		

		//////////////////
		// 사무소별 장비 재고 
		//////////////////
		if($office_id > 0) {

			// 총 결과수
			$total_rows = $this->stock_model->numRows($criteria);
			$config['total_rows'] = $total_rows;			

			$rows = $this->stock_model->getStocksWithPart($criteria, $num_rows, $offset);

		}
		//////////////////
		// 전체 재고 
		//////////////////
		else {
			$this->load->model('part_m', 'part_model');

			// 총 결과수
			$total_rows = $this->part_model->numRows($criteria);
			$config['total_rows'] = $total_rows;
			
			$rows = $this->part_model->getModelsBy($criteria, $num_rows, $offset);

		} 

		// 검색 조건이 있을 경우
		if($this->input->get()) {
			$config['suffix'] = '/?' . http_build_query($this->input->get());
			$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
		}

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		$data['rows'] = $rows;
		$data['total_rows'] = $total_rows;

		////////////////////
		//  필터링 데이터
		////////////////////
		$this->load->helper('form');

		// 장비 카테고리
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getAllPartCategories();
		$cats = gs2_convert_for_dropdown($cats);
		$cats['0'] = '--- 전체 ---';
		$data['category_filter'] = form_dropdown('cat_id', $cats, $this->input->get('cat_id'), 'id="category_filter" class="form-control"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--- 전체 ---';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $office_id, 'id="office_filter" class="form-control"');


		// 전체, 사무소별 구분
		if($office_id > 0) {
			$this->load->view('stock_list_by_office', $data);
		} else {
			$this->load->view('stock_list', $data);
		}

	}

	// 사무소별 재고 office
	public function office($office_id = 0) {
		if($office_id < 1) {
			redirect('stock/lists/?office_id=all');
		}

		$data['title'] = '재고 현황 - 사무소별';
		$data['current'] = 'page-stock';

		///////////////
		// 검색 조건
		///////////////
		$criteria = array();

		// 재고사무소
		if($this->input->get('off_id') !== false) {
			$criteria['office'] = $this->input->get('off_id');
		}

		// 장비 종류
		if($this->input->get('cat_id')){
			$criteria['category'] = $this->input->get('cat_id');
		}

		// 장비 모델 
		if($this->input->get('part_id')){
			$criteria['part'] = $this->input->get('part_id');
		}

		// 재고-장비 리스트는 join 된 테이블 이므로 MY_Mydel 의 getRowCount() 를 사용할 수 없음
		// 그래서 numRows 새로 정의
		$total_rows= $this->stock_model->numRows($criteria);
		$data['rows'] = $this->stock_model->getStocksWithPart($criteria);

		//============
		// pagination
		//============
		$this->load->library('pagination');

		$num_rows = 5;
		$base_url = base_url() . 'stock/office/';

		$config = array(
			'base_url' 		=> $base_url,
			'prefix'		=> '',
			'per_page'		=> $num_rows,
			'uri_segment'	=> 4,
			'num_links'		=> 5,
			'use_page_numbers'	=> TRUE,
			'page_query_string'	=> FALSE
		);

		// 총 결과수
		$config['total_rows'] = $total_rows;
		
		// 검색 조건이 있을 경우
		if($this->input->get()) {
			$config['suffix'] = '/?' . http_build_query($this->input->get());
			$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
		}

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $total_rows;
		

		/////////////
		//  필터링 
		/////////////
		$this->load->helper('form');

		// 장비 카테고리
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getAllPartCategories();
		$cats = gs2_convert_for_dropdown($cats);
		$cats['0'] = '--- 전체 ---';
		$data['category_filter'] = form_dropdown('cat_id', $cats, $this->input->get('cat_id'), 'id="category_filter" class="form-control"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--- 전체 ---';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $office_id, 'id="office_filter" class="form-control"');

		$this->load->view('stock_list_by_office', $data);
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