<?php
/**
 * 이관 업무 model 
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WorkTransfer_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Operation');
		$this->setTableName('gs2_operations');
	}

	public function all() {
		return $this->work_model->getOperations(GS2_OP_TYPE_TRANSFER);
	}

	// 시리얼 장비 찾기
	public function findBySerialNumber($op, $sn) {
		$this->load->model("waitpart_m", "waitpart_model");

		$criteria['serial_number'] = $sn;
		$criteria['gubun'] = 'D';
		$criteria['office']	 = $op->office->id;
		$criteria['qty'] = 0;

		// gs2_dump($criteria);
		// $criteria['status'] = '1';

		$result = $this->waitpart_model->search($criteria);

		// 실패시
		if(!$result) {
			return "장비를 찾을 수 없습니다";
		}

		// 성공시
		$wpart = $result[0];			// 대기 장비 정보
		$sp = $result[0]->serial_part;

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
			'wpart_id'		=> $wpart->id,		// 대기장비 ID
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
		return $item;
	}

	public function complete($op, $input) {
		;
	}

}