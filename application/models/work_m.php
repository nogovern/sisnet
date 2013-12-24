<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Operation');
		$this->setTableName('gs2_operations');
		
		$this->repo = $this->em->getRepository($this->getEntityName());
	}

	public function getByName($value) {
		return $repo->findBy(array('name' => $value));
	}

	// 작업번호 생성
	public function makeOperationNo() {
		$prefix = 'SYS';

		$idx = mt_rand(1, 9999);
		$no = $prefix . date('Ymd') . sprintf("%05d", $idx);

		return $no;
	}

	// 입고 목록
	public function getEnterList() {
		$criteria = array('type' => GS2_OPERATION_TYPE_ENTER);
		$rows = $this->repo->findBy($criteria);

		// Location 해석
		foreach($rows as $row) {
			if($row->getWorkLocation()) {
				$row->location_object = $this->parseLocation($row->getWorkLocation());
			}
		}

		return $rows;
	}

	// 설치 목록
	public function getInstallList() {
		$criteria = array('type' => '200');
		$rows = $this->repo->findBy($criteria);

		return $rows;
	}

	/**
	 * 해당 업무 완료
	 * 
	 * @return [type] [description]
	 */
	public function close($operation) {

	}

	// 철수 목록
	public function getEvaucationList() {
		$criteria = array('type' => '300');
		$rows = $this->repo->findBy($criteria);

		return $rows;
	}

	// 입고 업무 등록
	public function register($type, $post) {
		
		$part = $this->em->getReference('Entity\Part', $post['part_id']);
		$user = $this->em->getReference('Entity\User', $post['user_id']);
		$office = $this->em->getReference('Entity\Office', $post['office_id']);
		
		// 새로운 업무 객체
		$new = new Entity\Operation($this->em);

		$new->setType($post['work_type']);
		$new->setOperationNumber('SYS' . date("Ymd"));		// 새로운 O/N
		$new->setDateRegister();
		$new->setDateRequest($post['date_request']);
		$new->setStatus('1');
		$new->setMemo($post['memo']);

		$new->setOffice($office);
		$new->setUser($user);

		// 납품처 지정
		$new->setWorkLocation($part->company->id, GS2_LOCATION_TYPE_COMPANY);


		$this->work_model->_add($new);

		// $new_id = $new->id;		// 새로운 operation.id
		
		// 업무 대상 장비 리스트
		$item = new Entity\OperationPart;
		$item->setOperation($new);

		$item->setPart($part);
		$item->setType($post['work_type']);
		$item->setRequestQuantity($post['qty']);		// 요청수량
		$item->setDateRegister();
		$item->setNewFlag(TRUE);						// 신품

		$this->work_model->_add($item);

		// apply to db
		$this->work_model->_commit();
	}

	/**
	 * 작업 처리용 장비 임시 테이블
	 * 임시테이블에 아이템 추가 
	 * 
	 * @param [type]  $op          Operation class
	 * @param [type]  $part        Part class
	 * @param [type]  $val       part type - 1:시리얼넘버, 2:수량
	 * @param boolean $is_complete default FALSE;
	 * @param boolean $is_scan     default FALSE;
	 */
	public function addTempItem($op, $part, $val, $is_complete = FALSE, $is_scan = FALSE) {
		if(!($op instanceof Entity\Operation)){
			die('첫번째 인자는 Operation Class 여야 함!!!');
		}
		
		if(!($part instanceof Entity\Part)){
			die('2번째 인자는 Part Class 여야 함!!!');
		}

		// 수량장비일 경우 $val 은 숫자 형식이어야 함!
		if($part->type == 2 && !is_numeric($val)) {
			return FALSE;
		}

		$temp = new Entity\OperationTempPart;
		$temp->setOperation($op);
		$temp->setPart($part);
		$temp->setDateRegister();
		$temp->setPartType($part->type);
		$temp->setCompleteFlag($is_complete);

		// 임시로
		$loc = $this->work_model->parseLocation($op->getWorkLocation());
		$user = $loc->user;
		$temp->setuser($user);				// 처리 담당자
		
		if($part->type == 1){
			$temp->setSerialNumber($val);
		} else {
			$temp->setQuantity($val);
		}

		$this->work_model->_add($temp);
		$this->work_model->_commit();

		return $temp;
	}

	/**
	 * 임시테이블에서 장비 삭제
	 * 
	 * @param  class $item   OperationTempPart class
	 * @return [type]       [description]
	 */
	public function removeTempItem($item) {

	}

	public function updateTempItem($item, $val, $is_complete = FALSE, $is_scan = FALSE) {

	}

	
}



