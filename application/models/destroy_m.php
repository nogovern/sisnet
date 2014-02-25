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

	// 업무 생성
	public function create() {

	}

	// 업무 수정
	public function update() {

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
	public function addItem() {

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
	public function validateItem() {

		return false;
	}

	// ===================================
	//	 여기서 부터 custom method 정의 
	// ===================================
	
	// 장비 등록 때 사용되는 장비 카테고리 목록
	public function getPartCategories() {

	}

	// 장비 스캔 - 필요할 경우만
	public function scan() {

	}

	// 업무 프로세스 처리 
	// 	요청 -> 확정 -> 입력 -> 점포완료 -> 완료 -> 승인
	public function process($step = 2) {

	}
}

