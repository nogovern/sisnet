<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_m extends MY_Model {
	private $operation;

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Operation');
		$this->setTableName('gs2_operations');
		
		$this->repo = $this->em->getRepository($this->getEntityName());
	}

	// CI 에서는 모델 생성할 때 생성 인자를 전달할 수 없으므로
	// 모델 생성 후 operation entity 를 얻기 위해
	// 
	// $this->work_model->get(1) 과 동작은 비슷하지만 
	// 클래스 변수에 넣는다는 것이 틀리다. 
	// 안씀!!
	public function initialize($operation_id) {
		$this->operation = $this->repo->find($operation_id);
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
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where('w.type >= 100')
			->andWhere('w.type < 200')
			->orderBy('w.id', 'DESC');

		$rows = $qb->getQuery()->getResult();

		// 입고 업무의 작업 장소는 "납품처"
		foreach($rows as $row) {
			$row->store = gs2_decode_location($row->work_location);
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
			->andWhere('w.type < 300')
			->orderBy('w.id', 'DESC');

		$rows = $qb->getQuery()->getResult();

		// 설치 업무의 작업 장소는 "점포"
		foreach($rows as $row) {
			$row->store = gs2_decode_location($row->work_location);
		}
		return $rows;
	}

	// 철수 목록
	public function getEvaucationList() {
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where('w.type >= 300')
			->andWhere('w.type < 400')
			->orderBy('w.id', 'DESC');

		$rows = $qb->getQuery()->getResult();

		// 작업 장소는 "점포"
		foreach($rows as $row) {
			$row->store = gs2_decode_location($row->work_location);
		}
		return $rows;
	}

	public function getCloseList() {
		return $this->getEvaucationList();
	}

	///////////////
	// 입고 업무 생성
	///////////////
	public function createEnterOperation($type, $post) {
		
		// operation.id 를 얻기 위해 flush 를 해야 함
		$op = $this->addOperation($type, $post, TRUE);

		$post_data = array(
			'qty'		=> $post['qty'],
			'part_id'	=> $post['part_id'],
			'is_new'	=> TRUE,
		);

		$item = $this->addItem($op, $post_data, TRUE);
		// echo "Item : " . $item->id . "-" . $item->part->name; 

		return $op;
	}

	///////////////
	// 설치 업무 생성
	///////////////
	public function createInstallOperation($type, $post) {
		$op = $this->addOperation($type, $post);
		$this->em->flush();

		return $op;
	}

	///////////////
	// 철수 업무 생성 //
	///////////////
	public function createCloseOperation($type, $post) {
		$op = $this->addOperation($type, $post);
		$this->em->flush();

		return $op;
	}

	// 업무 메인 생성
	public function addOperation($type, $post, $do_flush=FALSE) {
		
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

		// 요청자
		$user = $this->em->getReference('Entity\User', $this->session->userdata('user_id'));
		$new->setUser($user);

		// 담당 사무소
		$office = $this->em->getReference('Entity\Office', $post['office_id']);
		$new->setOffice($office);

		// 입고 업무시 납품처 지정
		if($type >= '100' && $type < '200') {
			$part = $this->em->getReference('Entity\Part', $post['part_id']);
			$new->setWorkLocation(GS2_LOCATION_TYPE_COMPANY, $part->company->id);
		} else if( $type >= '200' && $type < '400') {
			$store = $this->em->getReference('Entity\Store', $post['store_id']);
			$new->setWorkLocation(GS2_LOCATION_TYPE_STORE, $store->id);
		}

		$this->em->persist($new);
		if($do_flush) {
			$this->em->flush();

		}
		return $new;
	}

	// 업무 메인 수정
	public function updateOperation($id, $data, $do_flush = FALSE) {
		$op = $this->repo->find($id);

		// 요청일 
		if(isset($data['date_request'])) {
			$op->setDateRequest($data['date_request']);
		}

		// 점포 작업일 
		if(isset($data['date_work'])) {
			$op->setDateWork($data['date_work']);
		}

		// 작업 완료일
		if(isset($data['date_finish'])) {
			$op->setDateFinish($data['date_finish']);
		}

		// 상태 변경
		if(isset($data['status'])) {
			$op->setStatus($data['status']);
		}

		// 담당자 변경
		if(isset($data['woker_id'])) {
			$worker = $this->em->getReference('Entity\User', $data['worker_id']);
			$op->setWorker($worker);
		}

		// 사무소 변경
		if(isset($data['office_id'])) {
			$office = $this->em->getReference('Entity\Office', $data['office_id']);
			$op->setOffice($office);
		}

		$op->setDateModify();

		// save & apply
		$this->em->persist($op);
		if($do_flush) {
			$this->em->flush();
		}

		return $op;
	}

	// 업무-장비 목록 생성(필요시)
	public function addItem($op, $data, $do_flush=FALSE) {
		$part = $this->em->getReference('Entity\Part', $data['part_id']);

		$item = new Entity\OperationPart;
		$item->setOperation($op);
		$item->setPart($part);
		$item->setQtyRequest($data['qty']);							// 요청수량
		$item->setNewFlag($data['is_new'] == 'Y' ? TRUE: FALSE);	// 신품 or 중고
		$item->setDateRegister();
		$item->setPartType($part->type);
		$item->setPartName($part->name);

		// 시리얼넘버 장비일 경우
		if($part->type == '1') {
			if(isset($data['serial_number']) && !empty($data['serial_number'])) {
				$item->setSerialNumber($data['serial_number']);
			}

			if(isset($data['serial_part_id']) && !empty($data['serial_part_id'])) {
				$sp = $this->em->getReference('Entity\SerialPart', $data['serial_part_id']);
				$item->setSerialPart($sp);
			}
		}

		// 직전위치 저장
		$location = '';
		if($op->type == '100') {
			$location = $op->work_location;		// 입고시는 납품처
		} elseif( $op->type >= '200' && $op->type < '300') {
			$location = 'O@' . $op->offfice->id;		// 설치시는 사무소
		} elseif( $op->type >= '300' && $op->type < '400') {
			$location = $op->work_location;		// 철수시는 점포
		} else {
			;
		}
		$item->setPreviousLocation($location);

		// 여분 필드 extra 배열이 있을경우
		if( isset($data['extra']) && count($data['extra'])) {
			$extra = $data['extra'];

			// 직전위치
			if(isset($extra['previous_location']) && !empty($extra['previous_location'])) {
				;
			}
		}	

		$this->em->persist($item);
		if($do_flush) {
			$this->em->flush();
		}

		return $item;
	}

	// 업무 장비 수정
	public function updateItem($item) {
		;
	}

	// 업무-장비 목록 삭제
	public function removeItem($item) {
		$this->em->remove($item);
	}

	// 업무-파일 생성(필요시)
	public function addFile($id, $data) {

	}

	// 업무-메모 생성
	public function addMemo($id, $data) {

	}
	

	// 업무-로그 생성
	public function addLog($id, $data, $do_flush = FALSE) {
		$op = $this->repo->find($id);
		if(!$op){	
			die("에러! operation 객체를 얻을 수 없음");
		}

		$log = new Entity\OperationLog;
		$writer = $this->em->getReference('Entity\User', $data['user_id']);
		$log->setUser($writer);
		$log->setOperation($op);
		$log->setContent($data['content']);
		$log->setType($data['type']);			// 로그 타입 (1: 시스템, 2:유저)
		if(isset($data['next_status'])) {
			$log->setNextStatus($data['next_status']);
		}
		$log->setDateRegister();

		$this->em->persist($log);
		if($do_flush) {
			$this->em->flush();
		}
		return $log;
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
		$loc = gs2_decode_location($op->getWorkLocation());
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

	public function nextStatus($id) {
		$op = $this->repo->find($id);
		if(!$op) {
			die("에러! operation 객체를 얻을 수 없음");
		}

		$op_data = array(
			'status' => $op->getStatus() + 1,
			);

		$this->updateOperation($id, $op_data);
	}

	// 장비 출고 
	public function deliveryItem($id) {
		$op = $this->repo->find($id);

		$items = $op->getItemList();
		foreach($items as $item) {
			$item->setQtyComplete($item->getQtyRequest());
			$item->setCompleteFlag(TRUE);

			$part = $this->em->getReference('Entity\Part', $item->part->id);
			$stock = $part->getStock($op->office->id);

			$qty = $item->getQtyRequest();
			$stock->setQtyS200($stock->qty_s200 - $qty);

			$this->em->persist($stock);
		}
	}

	/**
	 * [공통] 작업 요청 확정
	 * 
	 * @param  integer $id   	[description]
	 * @param  array  $data 	[description]
	 * @return object       	Operation Entity Object
	 */
	public function acceptRequest($id, $data = array()) {
		$op = $this->repo->find($id);

		$worker = $this->em->getReference('Entity\User', $data['worker_id']);
		$office = $this->em->getReference('Entity\Office', $data['office_id']);

		$op->setWorker($worker);
		$op->setOffice($office);
		$op->setDateWork($data['date_work']);
		$op->setMemo($data['memo']);
		$op->setDateModify();
		$op->setStatus('2');

		$this->em->persist($op);
		$this->em->flush();

		return $op;
	}	
}



