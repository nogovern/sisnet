<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	이동 컨트롤러
*/
class Move extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists() {
		$this->load->helper('form');

		$data['title'] = '이동 업무';
		$data['current'] = 'page-move';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getMoveList();

		// 사무소 select 생성
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = gs2_convert_for_dropdown($rows);
		$data['select_sender'] = form_dropdown('send_office_id', $arr_office, $this->session->userdata('office_id'), 'id="send_office_id" class="form-control required"');
		$data['select_receiver'] = form_dropdown('target_office_id', $arr_office, 0, 'id="target_office_id" class="form-control required"');
		
		$this->load->view('work/work_move_list', $data);
	}

	public function register() {
		$post_data = $this->input->post();
		// gs2_dump($post_data);
		// exit;

		if(!count($post_data)) {
			echo '작업중';
		} else {

			$post_data['op_type'] =  GS2_OP_TYPE_MOVE;		// 700
			$post_data['office_id'] = $this->input->post('send_office_id');
			$post_data['date_request'] = 'now';
			// $post_data['target_office_id'] = $this->input->post('target_office_id');

			// 업무 생성
			$op = $this->work_model->createOperation( GS2_OP_TYPE_MOVE, $post_data);

			// 로그 기록
			$log_data = array(
				'type'		=> '1',
				'content'	=> '이동 업무가 생성되었음',
				'event'		=> '생성'
			);
			$this->work_model->addLog($op, $log_data, TRUE);

			alert('이동업무 를 등록하였습니다.', site_url('/work/move'));
		}
	}

	public function view($id) {
		$this->load->helper('form');

		$data['title'] = "이동 업무 상세 보기";
		$data['current'] = 'page-move';
		$data['_config'] = $this->config->item('gs2');

		$op = $this->work_model->get($id);
		$data['op'] = $op;

		// 장비 카테고리 dropdown
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getValidPartCategories();
		$cats = gs2_convert_for_dropdown($cats);
		$data['select_category'] = form_dropdown('select_category', $cats, 0, 'id="select_category" class="form-control"');

		$this->load->view('work/work_move_view', $data);
	}

	public function update($id) {
		echo '작업중';
	}

	/**
	 * 이동 - ajax 요청 처리 전 공통 루틴 
	 * 
	 * @return boolean [description]
	 */
	private function ajax_setup() {
		
		return true;
	}

	// 이동 - 장비 발송 ajax 처리
	public function ajax_send() {

		$error = false;
		$op = $this->work_model->get($this->input->post('id'));

		if(!$op) {
			$error = true;
			$error_msg = '해당 업무가 없습니다';
		} else {
			$this->work_model->updateOperation($op, array('status' => '2'));
		}

		// 로그 기록
   		$log_data = array(
   			'type'		=> '1',
   			'content'	=> '[system] 수신 사무소로 발송합니다',
   			'event'		=> '입력'
   		);
   		$this->work_model->addLog($op, $log_data, TRUE);

		$oResult = new stdClass;
		$oResult->error = $error;
		$oResult->error_msg = ($error) ? $error_msg : '';

		echo json_encode($oResult);
	}

	// 이동 - 스캔 시리얼넙머 조회 ajax 응답
	// 	등록된 장비 리스트에서 조회한다
	public function ajax_retrieve($id = NULL) {
		$post= $this->input->post();
		
		// 응답 객체
		$response = new stdClass;
		$error = false;

		$op = $this->work_model->get($post['id']);
		// 업무 없을시 에러
		if(!$op) {
			$response->error = true;
			$response->error_msg = '존재하지 않는 업무 번호 입니다';
			echo json_encode($response);
			exit;
		} 

		// 시리얼넘버 없을시 에러
		if(strlen($post['serial_number']) == 0) {
			$response->error = true;
			$response->error_msg = '시리얼넘버를 입력하세요';
			echo json_encode($response);
			exit;
		}

		// 스캔 안된 장비 수 확인 

		// 이미 스캔된 장비인지 확인
		$items = $op->getItems();

		$is_found = false;
		foreach($items as $item) {
			// 있으면 gs2_operation_parts.id 를 반환한다
			if(!strcmp($item->serial_number, $post['serial_number'])) {
				$response->item['id'] = $item->id;
				$response->item['cat_id'] = $item->part->category->id;
				$response->item['part_id'] = $item->part->id;
				$response->item['is_new'] = $item->isNew();
				$response->item['qty'] = $item->qty_request;
				
				$is_found = true;
				break;
			}
		}

		if(!$is_found) {
			$error = true;
			$error_msg = '해당 시리얼넘버 장비를 찾을 수 없습니다';
		}

		$response->error = $error;
		$response->error_msg = ($error) ? $error_msg : '';

		echo json_encode($response);
	}

	// 이동 - 스캔 결과 저장
	// 	시리얼넘버 조회 안한 장비는 여기서 체크한다
	public function ajax_register_scan() {
		$post = $this->input->post();

		// 응답 객체
		$response = new stdClass;
		$error = false;

		$op = $this->work_model->get($post['id']);
		// 업무 없을시 에러
		if(!$op) {
			$response->error = true;
			$response->error_msg = '존재하지 않는 업무 번호 입니다';
			echo json_encode($response);
			exit;
		}

		// 스캔 안된 장비 수 확인

		// 이미 스캔된 장비인지 확인
		$items = $op->getItems();
		
		$found = false;				// 검색된 item entity
		// 조회 안된 장비를 찾는 경우
		if($post['item_id'] > 0) {
			foreach($items as $item) {
				if( $item->id  == $post['item_id']) {
					$found = $item;
					break;
				}
			}
		}
		// 조회안된 장비를 찾는 경우 
		else {
			foreach($items as $item) {
				if( $post['part_id'] == $item->part->id 
					&& $post['is_new'] == $item->is_new
					&& $post['qty'] == $item->qty_request ) 
				{
					$found = $item;
					break;	
				}
			}
		}

		if(!$found) {
			$error = true;
			$error_msg = "조건과 맞는 장비가 없습니다";
		} else {
			$argv = array(
				'is_scan'		=> true,
				'qty_scan'		=> $post['qty'],
				'qty_complete'	=> $post['qty'],
			);

			$this->work_model->updateItem($found, $argv, TRUE);

			// 결과에 추가
			$response->item_id = $found->id;
		}

		$response->error = $error;
		$response->error_msg = ($error) ? $error_msg : '';

		echo json_encode($response);
	}

	// 스캔 결과 초기화
	public function ajax_reset_scan($id) {
		$op = $this->work_model->get($id);

		$items = $op->getItems();

		foreach($items as $item) {
			$argv = array(
				'is_scan'		=> false,
				'qty_scan'		=> 0,
				'qty_complete'	=> 0,
			);

			$this->work_model->updateItem($item, $argv);
		}

		$this->work_model->_commit();

		// 응답 객체
		$response = new stdClass;

		$response->error = false;
		$response->error_msg = '';

		echo json_encode($response);
	}

	// 이동 - 업무 완료
	public function complete($id) {
		$op = $this->work_model->get($id);

		$items = $op->getItems();
	}
}