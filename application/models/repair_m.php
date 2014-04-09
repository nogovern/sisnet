<?php
/**
 * 수리 업무 model 
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repair_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Operation');
		$this->setTableName('gs2_operations');
	}

	public function all() {
		return $this->work_model->getOperations(GS2_OP_TYPE_REPAIR);
	}

	///////////////////////
	// 시리얼 장비 찾기
	///////////////////////
	public function findBySerialNumber($op, $sn) {

		// 페기-수리 대기 장비에서 검색은 일단 막음!
		if(0) {
			$this->load->model("waitpart_m", "waitpart_model");

			$criteria['serial_number'] = $sn;
			$criteria['gubun'] = 'D';
			$criteria['office']	 = $op->office->id;
			$criteria['qty'] = 0;

			$result = $this->waitpart_model->search($criteria);

			// 성공시
			$wpart = $result[0];			// 대기 장비 정보
			$sp = $result[0]->serial_part;
		} else {
			$result = true;
			$wpart = null;
			$this->load->model('part_m', 'part_model');
			$sp = $this->part_model->getPartBySerialNumber($sn, NULL, TRUE);
		}

		// 실패시
		if(!$result) {
			return "장비를 찾을 수 없습니다";
		}

		$prev_location = gs2_decode_location($sp->previous_location);
		if($prev_location) {
			$prev_location = $prev_location->name;
		}

		$info = array(
			'spart_id'		=> $sp->id,			// 시리얼장비 ID
			'category_id'	=> $sp->part->category->id,
			'part_id'		=> $sp->part->id,
			'serial_number'	=> $sp->serial_number,
			'prev_location' => $prev_location,
			'is_new'		=> $sp->is_new,
			'wpart_id'		=> ($wpart) ? $wpart->id : null,		// 대기장비 ID
		);

		return $info;		
	}

	// 장비 등록
	public function addItem($op, $input = array(), $do_flush = false) {
		$this->load->model('part_m', 'part_model');
		$part = $this->part_model->get($input['part_id']);

		if($part->type == '1') {
			$sp = $this->part_model->getPartBySerialNumber($input['serial_number'], NULL, TRUE);
		} 

		$sp_id = (isset($sp) && $sp) ? $sp->id : NULL;

		$post_data = array(
			'id'				=> $input['id'],		// 작업 ID
			'part_id'			=> $input['part_id'],
			'serial_number'		=> $input['serial_number'],
			'serial_part_id'	=> $sp_id,
			'qty'				=> $input['qty'],
			'is_new'			=> $input['is_new'],
		);

		$item = $this->work_model->addItem($op, $post_data, $do_flush);

		// 업무 상태가 1 이면 '입력' 상태로 변경
		if($item && $op->status == '1') {
			$this->work_model->updateOperation($op, array('status' => '2'), true);
		}

		return $item;
	}

	// 등록 장비 삭제
	public function removeItem($op, $item_id, $do_flush = false) {

		$item = $this->em->getReference('Entity\OperationPart', $item_id);
		if(!$item) {
			return '목록에 해당 장비가 없습니다';
		}

		$this->em->remove($item);
		if($do_flush) {
			$this->em->flush();
		}

		return true;
	}

	public function complete($op, $input = array()) {

		// 업무 메인 변경
		$data['status'] 		= '3';
		$data['date_finish'] 	= null;

		$this->work_model->updateOperation($op, $data, true);

		return true;
	}

}