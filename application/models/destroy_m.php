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

	// 아이템 추가 (장비등록)
	// 이미 등록된 아이템인지 확인 필요
	public function addItem($op, $data) {
		$error = false;

		// 모델 로딩
		$this->load->model('part_m', 'part_model');
		$this->load->model('waitpart_m', 'waitpart_model');

		$op = $this->work_model->get($data['id']);
		$part = $this->part_model->get($data['part_id']);

		$serial_number = $data['serial_number'];	// 시리얼넘버
		$serial_part_id = $data['serial_id'];

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

			// 요청수량이 클 경우
			if($wp->qty < $data['qty']) {
				$response['error'] = true;
				$response['error_msg'] = sprintf("해당 장비의 폐기 가능 최대 수량은 %d 개 입니다", $wp->qty);
				echo json_encode($response);
				exit;
			// 가능 수량이 없을 경우	
			} else if ($wp->qty == 0) {
				$response['error'] = true;
				$response['error_msg'] = sprintf("폐기 상태의 장비가 없으므로, 선택할 수 없습니다");
				echo json_encode($response);
				exit;
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

		return ($response);
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

	// 배열로 업무 아이템 다중 등록
	public function multi_add($id, $data) {
		if(!is_array($data)) {
			return false;
		}

		// load model
		$this->load->model("part_m", "part_model");

		$op = $this->work_model->get($id);

		// 결과 저장
		$count['success'] = 0;
		$count['fail'] = 0;

		$insert_data = array();
		foreach($data as $idx => $row) {

			$p_type = ($row[0] == '시리얼') ? "1" : "2";
			$p_model = $row[3];
			$qty = intval($row[5]);

			// 장비 모델명으로 장비 Entity 얻기
			$part = $this->em->getRepository('Entity\Part')->findOneBy(array('name' => "$p_model"));

			if($part) {
				$count['success']++;
				echo $part->name;
			} else {
				$count['fail']++;
				continue;
			}

			// 폐기리스트에서 검색
			$qb = $this->em->createQueryBuilder();
			$qb->select("wp")
				->from("Entity\WaitPart", "wp")
				->where("wp.gubun = 'D' ")
				->andWhere("wp.part = {$part->id}")
				->andWhere("wp.part_type = $p_type")
				->andWhere("wp.office = {$op->office->id}");

			$result = $qb->getQuery()->getResult();
			$wpart = $result[0];
			echo " - " . $wpart->qty_accept;

			// 아이템 등록 용 배열
			$insert_data['id'] = $id;
			$insert_data['part_id'] = $part->id;
			$insert_data['serial_number'] = $row[1];
			$insert_data['serial_id'] = '';
			$insert_data['qty'] = $qty;
			$insert_data['is_new'] = 'N';
			$insert_data['wpart_id'] = $wpart->id;

			//$this->addItem($op, $insert_data);

			// 아이템 등록 후 폐기 목록의 장비 정보 수량 변경
			$wpart->decrease($qty ,2);

			// $this->em->persist($wpart);
			// $this->em->flush();

		}	

		return $count;
	}
}

