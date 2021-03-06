<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	수리 컨트롤러
*/
class Repair extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists($page = 1) {

		$this->load->helper('form');

		$data['title'] = '수리 업무';
		$data['current'] = 'page-repair';

		///////////////
		// 검색 조건
		///////////////
		$criteria = array();
		
		// 상태
		if($this->input->get('status')) {
			$criteria['status'] = $this->input->get('status');
		}

		// 형태
		if($this->input->get('type')) {
			$criteria['type'] = $this->input->get('type');
		}

		// 사무소 - GET 유무 확인시 없을떄 false 로 타입까지 비교해야 함
		if($this->input->get('off_id') === false) {
			$criteria['office'] = (gs2_user_type() == '1') ? $this->session->userdata('office_id') : 0;
		} else {
			$criteria['office'] = $this->input->get('off_id');
		}

		// pagination 초기화
		$config = $this->work_model->setPaginationConfig('work/repair/lists/');

		$data['rows'] = $this->work_model->getOperations(GS2_OP_TYPE_REPAIR, $criteria, GS2_LIST_PER_PAGE, $page);
		// 총 결과수
		$total_rows = $this->work_model->numRows(GS2_OP_TYPE_REPAIR, $criteria);
		$config['total_rows'] = $total_rows;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $total_rows;

		// ===============
		//  필터링 데이터
		// ===============
		$this->load->helper('form');

		// 진행상태
		$data['status_filter'] = form_dropdown('status', gs2_op_status_list('2'), $this->input->get('status'), 'id="status_filter" class="form-control input-sm"');

		// 작업형태
		$type_list = array(
			'0'	=> '-- 전체 --',	
			'501'	=> '수리-출고',	
			'502'	=> '수리-입고',	
		);
		
		$data['type_filter'] = form_dropdown('type', $type_list, $this->input->get('type'), 'id="type_filter" class="form-control input-sm"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--전체--';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $criteria['office'], 'id="office_filter" class="form-control input-sm"');

		/////////////////////////// modal 요청서 용 ////////
		
		// 사무소 select 생성
		$data['select_office'] = form_dropdown('select_office', $arr_office, $this->session->userdata('office_id'), 'id="select_office" class="form-control required"');

		// 수리 업체 선택
		$this->load->model('company_m', 'company_model');
		$companies = gs2_convert_for_dropdown($this->company_model->getClients('6'));	// 임시
		// $companies = gs2_convert_for_dropdown($this->company_model->getClients('5'));
		$data['select_company'] = form_dropdown('select_company', $companies, 0, 'id="select_company" class="form-control"');
		
		$this->load->view('work/work_repair_list', $data);

	}

	/////////////////
	// 상세 보기
	/////////////////
	public function view($id) {
		// echo "수리업무 상세 보기 작업중";
		// exit;

		$data['title'] = "수리 업무 상세 보기";
		$data['current'] = 'page-repair';
		$data['_config'] = $this->config->item('gs2');

		$work = $this->work_model->get($id);
		$data['op'] = $work;

		// 장비 카테고리 dropdown
		$this->load->helper('form');
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getValidPartCategories();
		$cats = gs2_convert_for_dropdown($cats);
		$data['select_category'] = form_dropdown('select_category', $cats, 0, 'id="select_category" class="form-control"');

		$this->load->view('work/work_repair_view', $data);
	}

	/////////////////
	// 요청 등록
	/////////////////
	public function register() {
		gs2_dump($this->input->post());
		// exit;

		$post_data['office_id'] = $this->input->post('select_office');
		$post_data['op_type'] =  $this->input->post('op_type');
		$post_data['company_id'] = $this->input->post('select_company');	// 수리 업체
		$post_data['date_request'] = '';

		// 업무 생성
		$op = $this->work_model->createOperation( $this->input->post('op_type'), $post_data);

		// 로그 기록
		$log_data = array(
			'type'		=> '1',
			'content'	=> '수리 업무가 생성되었음',
			'event'		=> '생성'
		);
		$this->work_model->addLog($op, $log_data, TRUE);

		redirect('work/repair');
	}

	/////////////////
	// 요청 삭제(취소)
	/////////////////
	public function delete($id) {
		
	}

}