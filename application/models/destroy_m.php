<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 페기 작업 모델
*/
class Destroy_m extends MY_Model implements IOperationModel
{
	public function __construct() {
		parent::__construct();

		$this->load->model('work_m', 'work_model');
		$this->load->model('part_m', 'part_model');
	}

	// 업무 요청 (생성)
	public function create() {

	}

	// 업무 수정
	public function update() {

	}

	// 요청 확정
	public function accept() {

	}

	// 업무 완료
	public function complete($op) {
		$op_data["status"] = "3";
		$op_data['date_finish'] = 'now';

		$this->work_model->updateOperation($op, $op_data);

		// 업무 log 생성
		$log_data = array(
			'type' => '1',
			'content' => gs2_op_type($op->type) . ' 작업완료 합니다',
			'event'			=> '완료'
		);
		$this->work_model->addLog($op, $log_data, TRUE);

		return true;
	}

	// 업무 종료 - 승인
	public function close() {

	}

	// 업무 요청 취소
	public function cancel() {
		$this->remove();
	}

	// 업무 삭제
	public function remove() {

	}

	// 업무 리스트
	public function getList($type, $criteria=array(), $limit = 0, $page = 1, $order=array()) {
		return $this->work_model->getOperations($type, $criteria, $limit, $page);
	}

	// 폐기 장비 등록 분기
	public function addItem($op, $data) {
		if($op->type == '601') {
			return $this->addItemForAccept($op, $data);
		} else {
			return $this->addItemForSend($op, $data);
		}
	}

	// 폐기-승인 용 장비 등록
	public function addItemForAccept($op, $data) {

		// 모델 로딩
		$this->load->model('waitpart_m', 'waitpart_model');

		$op = $this->work_model->get($data['id']);
		$part = $this->part_model->get($data['part_id']);

		$serial_number = $data['serial_number'];	// 시리얼넘버
		$serial_part_id = $data['serial_id'];

		if($part->type == '1' && empty($serial_part_id)) {
			return "시리얼장비는 시리얼넘버 or 직전위치 로 검색하셔야 합나다";
			exit;
		}

		if($part->type == '3') {
			return "소모품은 선택할 수 없습니다.";
		}

		// 수량장비
		if($part->type == '2') {
			// 폐기 장비 중에서 검색
			$result = $this->waitpart_model->existPartInList("D", $op->office->id, $part->id, '1');
			if(!$result) {
				return sprintf("재고 사무소의 폐기 대상에 \n\"%s\" \n장비가 없습니다", $part->name);
			} 

			$wp = $result[0];	// 대기 장비

			// 요청수량이 클 경우
			if($wp->qty < $data['qty']) {
				return sprintf("해당 장비의 폐기 가능 최대 수량은 %d 개 입니다", $wp->qty);
			// 가능 수량이 없을 경우	
			} else if ($wp->qty == 0) {
				return sprintf("폐기 상태의 장비가 없으므로, 선택할 수 없습니다");
			}
			
			$wpart_id = $wp->id;
		}
		// 시리얼장비
		else {
			$wpart_id = $data["wpart_id"];
			$wp = $this->waitpart_model->get($wpart_id);
		}

		$get_data = array(
			'id'				=> $data['id'],		// 작업 ID
			'part_id'			=> $data['part_id'],
			'serial_number'		=> $serial_number,
			'serial_part_id'	=> $serial_part_id,
			'qty'				=> $data['qty'],
			'is_new'			=> $data['is_new'],
			'extra'				=> $wpart_id,
		);

		// 폐기대기 리스트 내 수량 변경
		$wp->minus($data['qty'], 1);
		$wp->add($data['qty'], 2);
		$this->em->persist($wp);
		
		// 대기 장비 상태를 변경
		//$this->waitpart_model->update($wpart_id, array("status" => '2'));

		// id를 얻기 위해 일단 flush
		$item = $this->work_model->addItem($op, $get_data, TRUE);

		if(!$item) {
			return "장비 등록에 실패하였습니다.\n 관리자에게 문의 바랍니다";
		}

		// 성공시
		return $item;
	}

	// 아이템 목록
	public function getItems() {

	}

	// 아이템 얻기
	public function getItem($index = 0) {

	}

	// 아이템 검색
	public function findItem() {

	}

	// 아이템 삭제
	public function removeItem() {

	}

	// 아이템 모두 삭제
	public function removeItemAll() {

	}

	// 아이템 등록 가능 여부 검사
	// 
	// 	업무 마다 등록 시 검사하는 목록이 틀리다
	public function validateItem($data = array()) {
		
		return false;
	}

	// ===================================
	//	 여기서 부터 custom method 정의 
	// ===================================
	
	// 장비 등록 때 사용되는 장비 카테고리 목록
	//   수량장비 제외
	public function getPartCategories() {

	}

	// 장비 등록 전 체크
	// - 페기 대기 장비 중에서 가능 수량이 있는 장비만 등록 가능
	public function checkItem() {

	} 

	// 장비 스캔
	// - 폐기대기 장비 중에서 승인 수량만 가능
	public function scan() {
		
	}

	// 업무 프로세스 처리 
	// 	요청 -> 확정 -> 입력 -> 점포완료 -> 완료 -> 승인
	public function process($step = 2) {

	}

	// 폐기-철수 장비 등록
	private function addItemForSend($op, $data) {
		return $this->addItem2($op->id, $data);
	}

	// 폐기 업무 장비 1개 등록 
	public function addItem2($id, $data = array()) {
		
		// work_model->addItem() 용 배열로 변환
		$op = $this->work_model->get($id);
		$part = $this->part_model->get($data['part_id']);

		$item_data['id'] 				= $id;
		$item_data['part_id'] 			= $data['part_id'];
		$item_data['serial_part_id'] 	= $data['serial_id'];
		$item_data['serial_number'] 	= $data['serial_number'];
		$item_data['qty'] 				= $data['qty'];
		$item_data['is_new'] 			= $data['is_new'];

		// 업무 장비 신규 등록
		$new_item = $this->work_model->addItem($op, $item_data, false);

		return $new_item;
	}

	// 엑셀 파일에서 읽은 배열로 장비 1개 등록
	public function addItemFromExcel($id, $row) {

		$p_type = ($row[0] == '시리얼') ? "1" : "2";
		$p_model = $row[3];
		$qty = intval($row[5]);

		// 장비 모델명으로 장비 Entity 얻기
		$part = $this->em->getRepository('Entity\Part')->findOneBy(array('name' => "$p_model"));

		if(!$part) {
			return false;
		}

		// 시리얼 장비인 경우
		if($part->type == '1') {
			if(strlen($row[1]) > 0) {
				;
			}
		}

		$data['part_id'] 		= $part->id;
		$data['serial_id'] 		= NULL;
		$data['serial_number'] 	= $row[1];
		$data['qty'] 			= $qty;
		$data['is_new'] 		= 'N';

		$item = $this->addItem2($id, $data);
		return $item;

	}
	
}

