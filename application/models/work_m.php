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
	private function makeOperationNumber() {
		$prefix = 'ON';

		$idx = mt_rand(1, 9999);
		$no = $prefix . date('Ymd') . sprintf("%05d", $idx);

		return $no;
	}

	/**
	 * 업무 완료
	 * 
	 * @param  [type] $operation [description]
	 * @return [type]            [description]
	 */
	public function close($operation) {
		;
	}

	/**
	 * 입고 목록
	 * 
	 * @return [type] [description]
	 */
	public function getEnterList() {
		$criteria = array('type' => GS2_OP_TYPE_ENTER);
		$rows = $this->repo->findBy($criteria);

		// Location 해석
		foreach($rows as $row) {
			if($row->getWorkLocation()) {
				$row->location_object = $this->parseLocation($row->getWorkLocation());
			}
		}

		return $rows;
	}

	/**
	 * 설치 업무 목록 - doctrine query builder 사용
	 * 
	 * @return  array of objects [description]
	 */
	public function getInstallList() {
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where('w.type >= 200')
			->andWhere('w.type < 300');

		$rows = $qb->getQuery()->getResult();

		// 설치 업무의 작업 장소는 "점포"
		foreach($rows as $row) {
			$row->store = $this->parseLocation($row->work_location);
		}
		return $rows;
	}

	// 철수 목록
	public function getEvaucationList() {
		$criteria = array('type' => '300');
		$rows = $this->repo->findBy($criteria);

		return $rows;
	}

	///////////////
	// 설치 업무 생성
	///////////////
	public function create_install_operation($type, $post) {
		$op = $this->add_operatoin($type, $post);
		$this->em->flush();

		return $op;
	}

	///////////////
	// 입고 업무 생성
	///////////////
	public function create_enter_operation($type, $post) {
		$op = $this->add_operatoin($type, $post);

		// 장비
		$extra = array('is_new'	=> TRUE);
		$part = $this->em->getReference('Entity\Part', $post['part_id']);
		$item = $this->add_op_item($op, $part, $post['qty'], $extra);
		$this->em->flush();

		return $op;
	}

	// 철수 업무 생성
	public function create_evacuate_operation($type, $post) {
		
	}

	// 업무 메인 생성
	public function add_operatoin($type, $post) {
		$user = $this->em->getReference('Entity\User', $post['user_id']);
		$office = $this->em->getReference('Entity\Office', $post['office_id']);
		
		// 새로운 업무 객체
		$new = new Entity\Operation;

		$new->setType($post['op_type']);
		$new->setOperationNumber($this->makeOperationNumber());		
		$new->setDateRegister();
		$new->setDateRequest($post['date_request']);
		$new->setStatus('1');
		$new->setMemo($post['memo']);
		if(isset($post['date_work'])) {
			$new->setDateWork($post['date_work']);
		}

		$new->setOffice($office);
		$new->setUser($user);

		// 입고 업무시 납품처 지정
		if($type >= '100' && $type < '200') {
			$part = $this->em->getReference('Entity\Part', $post['part_id']);
			$new->setWorkLocation(GS2_LOCATION_TYPE_COMPANY, $part->company->id);
		} else if( $type >= '200' && $type < '300') {
			$store = $this->em->getReference('Entity\Store', $post['store_id']);
			$new->setWorkLocation(GS2_LOCATION_TYPE_STORE, $store->id);
		}

		$this->em->persist($new);
		return $new;
	}

	// 업무-장비 목록 생성(필요시)
	public function add_op_item($op, $part, $qty=1, $extra = array()) {
		$item = new Entity\OperationPart;

		$item->setOperation($op);
		$item->setPart($part);
		$item->setType($op->type);
		$item->setQtyRequest($qty);						// 요청수량
		$item->setNewFlag($extra['is_new']);			// 신품 or 중고
		$item->setDateRegister();

		$this->em->persist($item);

		return $item;
	}

	// 업무-파일 생성(필요시)
	public function add_op_file($op, $data) {

	}

	// 업무-메모 생성
	public function add_op_comment($op, $data) {

	}
	

	// 업무-로그 생성
	public function add_op_log($op, $data) {

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
	public function addTempItem($op, $part, $val, $is_scan = FALSE, $is_complete = FALSE) {
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
		$loc = $this->parseLocation($op->getWorkLocation());
		$user = $loc->user;
		$temp->setuser($user);				// 처리 담당자
		
		if($part->type == 1){
			$temp->setSerialNumber($val);
		} else {
			$temp->setQuantity($val);
		}

		$this->_add($temp);
		$this->_commit();

		return $temp;
	}

	/**
	 * 임시테이블에서 장비 삭제
	 * 
	 * @param  class $item   OperationTempPart class
	 * @return [type]       [description]
	 */
	public function removeTempItem($item) {
		$this->em->remove($item);
		$this->em->flush();
	}

	public function updateTempItem($item, $val, $is_scan = FALSE, $is_complete = FALSE) {
		$item->setScanFlag($is_scan);
		$item->setCompleteFlag($is_complete);

	}

	
}



