<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	폐기 컨트롤러
*/
class Destroy extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
		$this->load->model('waitpart_m', 'waitpart_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists($page = 1) {
		$this->load->helper('form');

		$data['title'] = '폐기 업무';
		$data['current'] = 'page-destroy';

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
		$config = $this->work_model->setPaginationConfig('work/destroy/lists/');

		$data['rows'] = $this->work_model->getOperations(GS2_OP_TYPE_DESTROY, $criteria, GS2_LIST_PER_PAGE, $page);
		// 총 결과수
		$total_rows = $this->work_model->numRows(GS2_OP_TYPE_DESTROY, $criteria);
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
			'601'	=> '폐기-승인',	
			'602'	=> '폐기-출고',	
		);
		
		$data['type_filter'] = form_dropdown('type', $type_list, $this->input->get('type'), 'id="type_filter" class="form-control"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--전체--';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $criteria['office'], 'id="office_filter" class="form-control"');

		/////////////////////////// modal 요청서 용 ////////
		// 사무소 select 생성
		$data['select_office'] = form_dropdown('select_office', $arr_office, $this->session->userdata('office_id'), 'id="select_office" class="form-control required"');
		
		$this->load->view('work/work_destroy_list', $data);
	}

	////////////
	// 요청 등록
	////////////
	public function register() {
		// $post_data = $this->input->post();


		if(!count($this->input->post())) {
			echo '작업중';
		} else {

			$post_data['op_type'] =  $this->input->post('op_type');
			$post_data['office_id'] = $this->input->post('select_office');
			$post_data['date_request'] = date("now");


			// 업무 생성
			$op = $this->work_model->createOperation( $this->input->post('op_type'), $post_data);

			// 로그 기록
			$log_data = array(
				'type'		=> '1',
				'content'	=> '폐기 업무가 생성되었음',
				'event'		=> '생성'
			);
			$this->work_model->addLog($op, $log_data, TRUE);

			redirect('work/destroy');
		}

	}

	public function view($id) {
		$this->load->helper('form');

		$data['title'] = "폐기 업무 상세 보기";
		$data['current'] = 'page-destroy';
		$data['_config'] = $this->config->item('gs2');

		$op = $this->work_model->get($id);
		$data['op'] = $op;

		// 장비 카테고리 dropdown
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getValidPartCategories();
		$cats = gs2_convert_for_dropdown($cats);
		$data['select_category'] = form_dropdown('select_category', $cats, 0, 'id="select_category" class="form-control"');

		$this->load->view('work/work_destroy_view', $data);
	}

	public function update($id) {
		echo '작업중';
	}

	// 폐기 승인 장비 등록
	public function addItem() {
		$error = false;

		$this->load->model('part_m', 'part_model');

		$op = $this->work_model->get($this->input->get('id'));
		$part = $this->part_model->get($this->input->get('part_id'));

		$serial_number = $this->input->get('serial_number');	// 시리얼넘버
		$serial_part_id = $this->input->get('serial_part_id');

		
		if($part->type == '1' && empty($serial_part_id)) {
			$response['error'] = true;
			$response['error_msg'] = "시리얼장비는 시리얼넘버 or 직전위치 로 검색하셔야 합나다";
			echo json_encode($response);
			exit;
		}

		if($part->type == '3') {
			$response['error'] = true;
			$response['error_msg'] = "소모품은 선택할 수 없습니다.";
			echo json_encode($response);
			exit;
		}

		// 수량장비
		if($part->type == '2') {
			// 폐기 장비 중에서 검색
			$result = $this->waitpart_model->existPartInList("D", $op->office->id, $part->id, '1');
			if(!$result) {
				$response['error'] = true;
				$response['error_msg'] = sprintf("재고 사무소의 폐기 대상에 \n\"%s\" \n장비가 없습니다", $part->name);
				echo json_encode($response);
				exit;
			} 

			$wp = $result[0];	// 대기 장비
			if($this->input->get('qty') != $wp->qty) {
				$response['error'] = true;
				$response['error_msg'] = sprintf("해당 장비의 폐기 가능 수량은 %d 개 입니다", $wp->qty);
				echo json_encode($response);
				exit;	
			}
			
			$wpart_id = $wp->id;

		}
		// 시리얼장비
		else {
			$wpart_id = $this->input->get("wpart_id");
		}

		$get_data = array(
			'id'				=> $this->input->get('id'),		// 작업 ID
			'part_id'			=> $this->input->get('part_id'),
			'serial_number'		=> $serial_number,
			'serial_part_id'	=> $serial_part_id,
			'qty'				=> $this->input->get('qty'),
			'is_new'			=> $this->input->get('is_new'),
			'extra'				=> $wpart_id,
		);

		// 대기 장비 상태를 변경
		$this->waitpart_model->update($wpart_id, array("status" => '2'));

		// id를 얻기 위해 일단 flush
		$item = $this->work_model->addItem($op, $get_data, TRUE);

		if(!$item) {
			$error = true;
			$error_msg = "장비 등록에 실패하였습니다.\n 관리자에게 문의 바랍니다";
		}

		// json 결과 객체
		$response = new stdClass;	
		if($error) {
			$response->error = true;
			$response->error_msg = $error_msg;
		} else {
			$response->error = false;
			$response->error_msg = '';
			$response->id = $item->id;			// 새로운 opertaion_parts.id
		}

		echo json_encode($response);
	}

	// 폐기 승인 목록에 등록된 아이템 삭제
	public function removeItem() {
		$em = $this->work_model->getEntityManager();

		$op = $this->work_model->get($this->input->get("id"));
		$item = $em->getReference('Entity\OperationPart', $this->input->get("item_id"));

		// extra 필드에 대기장비 id 저장되어 있음
		$this->waitpart_model->update($item->extra, array('status' => '1'));
			
		$result['msg'] = sprintf('%s 장비를 목록에서 삭제하였습니다', $item->part_name);
		$this->work_model->removeItem($item, true);

		echo json_encode($result);
	}
	

	// 폐기 출고 장비 스캔
	public function scanItem() {

	}

}