<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	이관 컨트롤러
*/
class Transfer extends CI_Controller
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

		$data['title'] = '이관 업무';
		$data['current'] = 'page-transfer';

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
		$config = $this->work_model->setPaginationConfig('work/transfer/lists/');

		$data['rows'] = $this->work_model->getOperations(GS2_OP_TYPE_TRANSFER, $criteria, GS2_LIST_PER_PAGE, $page);
		// 총 결과수
		$total_rows = $this->work_model->numRows(GS2_OP_TYPE_TRANSFER, $criteria);
		$config['total_rows'] = $total_rows;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $total_rows;

		// ===============
		//  필터링 데이터
		// ===============
		$this->load->helper('form');

		// 진행상태
		$data['status_filter'] = form_dropdown('status', gs2_op_status_list('2'), $this->input->get('status'), 'id="status_filter" class="form-control"');

		// 작업형태
		$type_list = array(
			'0'	=> '-- 전체 --',	
			'801'	=> '이관-입고',	
			'802'	=> '이관-출고',	
		);
		
		$data['type_filter'] = form_dropdown('type', $type_list, $this->input->get('type'), 'id="type_filter" class="form-control"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--전체--';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $criteria['office'], 'id="office_filter" class="form-control"');

		/////////////////////////// modal 요청서 용 ////////
		
		// 사무소 select 생성
		$data['select_office'] = form_dropdown('select_office', $arr_office, $this->session->userdata('office_id'), 'id="select_office" class="form-control required" disabled="disabled" ');

		// 업체 선택
		$this->load->model('company_m', 'company_model');
		$companies = gs2_convert_for_dropdown($this->company_model->getClients());
		$data['send_company'] = form_dropdown('send_company', $companies, 0, 'id="send_company" class="form-control"');
		$data['receive_company'] = form_dropdown('receive_company', $companies, 0, 'id="receive_company" class="form-control"');
		
		$this->load->view('work/work_transfer_list', $data);

	}

	public function view($id) {

	}

	public function register() {
		gs2_dump($this->input->post());
	}

	public function delete($id) {
		
	}

}