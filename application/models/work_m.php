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

	// 가장 최근 생성된 업무번호
	public function getMaxOperationNumber() {
		// $dql = "select substr(max(op.operation_number),3,8) from Entity\Operation op";
		$dql = "SELECT max(op.operation_number) FROM Entity\Operation op";
		$query = $this->em->createQuery($dql);

		return $query->getSingleScalarResult();
	}

	/**
	 * 업무번호 생성
	 * 
	 * @return string  형식은 "ON" + "YYYYMMDD" + "00001"
	 */
	public function makeOperationNumber() {
		$prefix = 'ON';

		$max = $this->getMaxOperationNumber();

		$today = new DateTime();
		$d1 = $today->format('Ymd');		// 오늘 날짜

		$d2 = substr($max, 2, 8);			// 작업번호 날짜

		if(!strcmp($d1, $d2)) {
			$number = intval(substr($max, 10,5)) + 1;
		} else {
			$number = 1;
		}

		// 새로운 업무 번호
		$new = sprintf('%s%s%05d', $prefix, $d1, $number);

		return $new;
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
	 * [공통] 업무 목록 
	 * 
	 * @param  string $type 100/200/300 ...
	 * @return [type]       [description]
	 */
	protected function _getOpList($type) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where('w.type >= :type')
			->andWhere('w.type < :type2')
			->orderBy('w.id', 'DESC');

		$qb->setParameter('type', $type);
		$qb->setParameter('type2', $type+100);

		$rows = $qb->getQuery()->getResult();

		// need refactoring
		// -- 기능의 최소화
		// -- 여기서 하는것 보다 컨드롤 에서 하는게 나을 듯...
		foreach($rows as $row) {
			$row->store = ($row->work_location) ? gs2_decode_location($row->work_location) : '';
		}
		
		return $rows;
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
	public function getCloseList() {
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

	// 상태변경 업무 목록
	public function getChangeList() {
		return $this->_getOpList(900);
	}

	// 교체 업무 목록
	public function getReplaceList() {
		return $this->_getOpList(400);
	}

	// 이동 업무 목록
	public function getMoveList() {
		return $this->_getOpList(500);
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

		// 총 입고 요청 수량
		$request_qty = intval($post['qty']);

		// 장비 정보 다시!
		$part = $this->em->getRepository('Entity\Part')->find($post['part_id']);
		
		// 시리얼장비 수량 만큼 반복
		if( $part->type == 1) {
			for($i = 0; $i < $request_qty; $i++) {
				$post_data['qty'] = 1;					// 시리얼 장비는 1개 
				$this->addItem($op, $post_data);
			}
		} else {
			$item = $this->addItem($op, $post_data);
		}

		// 장비 재고량 변경
		$stock = $part->getStock($op->office->id);
		$stock->setQtyS100($stock->qty_s100 + $request_qty);	// 발주 수량 update

		/////////////////
		// 업무 log 생성
		/////////////////
		$log_data = array(
			'content' 	=> '[시스템] 입고 요청 생성',
			'type' => '2',
			'next_status' => '1',
			);

		$this->addLog($op, $log_data);

		/////////////////
		// 한번에 flush
		/////////////////
		$this->em->flush();

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

	///////////////
	// 업무 메인 생성 
	///////////////
	public function addOperation($type, $post, $do_flush=FALSE) {
		
		// 새로운 업무 객체
		$new = new Entity\Operation;

		$new->setType($post['op_type']);
		$new->setOperationNumber($this->makeOperationNumber());		
		$new->setDateRegister();
		$new->setDateRequest($post['date_request']);
		$new->setStatus('1');
		$new->setMemo($post['memo']);
		// 점포 개점일 or 폐점일
		if(isset($post['date_store'])){
			$new->setDateStore($post['date_store']);
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
	public function updateOperation($op, $data, $do_flush = FALSE) {
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

		// 작업(방문) 예정일 
		if(isset($data['date_expect'])) {
			$op->setDateExpect($data['date_expect']);
		}

		// 점포 개/폐점일 
		if(isset($data['date_store'])) {
			$op->setDateStore($data['date_store']);
		}

		// 상태 변경
		if(isset($data['status'])) {
			$op->setStatus($data['status']);
		}

		// 담당자 변경
		if(isset($data['worker_id'])) {
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
		$this->load->model('part_m', 'part_model');
		$part = $this->part_model->get($data['part_id']);

		$item = new Entity\OperationPart;
		$item->setOperation($op);
		$item->setPart($part);
		$item->setQtyRequest($data['qty']);							// 요청수량
		$item->setNewFlag($data['is_new'] == 'Y' ? TRUE: FALSE);	// 신품 or 중고
		$item->setDateRegister();
		$item->setPartType($part->type);
		$item->setPartName($part->name);

		// 시리얼넘버 장비일 경우
		// 장비 찾아서 입력
		if($part->type == '1' && isset($data['serial_number'])) {
			if(!empty($data['serial_number'])) {
				$item->setSerialNumber($data['serial_number']);
				$sp = $this->part_model->getPartBySerialNumber($data['serial_number']);
				$item->setSerialPart($sp);
			}
		}

		// 직전위치 저장
		$location = '';
		if($op->type == '100') {
			$location = $op->work_location;		// 입고시는 납품처
		} elseif( $op->type >= '200' && $op->type < '300') {
			$location = 'O@' . $op->office->id;		// 설치시는 사무소
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
	public function updateItem($item, $data, $do_flush=FALSE) {
		// 시리얼넘버
		if(isset($data['serial_number'])) {
			$item->setSerialNumber($data['serial_number']);
		}

		// 확인 수량
		if(isset($data['qty_complete'])) {
			$item->setQtyComplete($data['qty_complete']);
		}

		// 분실 수량(시리얼장비만 사용할 건가??)
		if(isset($data['qty_lost'])) {
			$item->setQtyLost($data['qty_lost']);
		}

		// 스캔 여부
		if(isset($data['is_scan'])) {
			$item->setSacnFlag($data['is_scan']);
		}

		$this->em->persist($item);
		if($do_flush) {
			$this->em->flush();
		}

		return $item;
	}

	// 업무-장비 목록 삭제
	public function removeItem($item) {
		$this->em->remove($item);
	}

	// 업무-파일 생성(필요시)
	public function addFile($op, $data) {

	}

	// 업무-메모 생성
	public function addMemo($op, $data) {

	}
	
	// 업무-로그 생성
	public function addLog($op, $data, $do_flush = FALSE) {
		if(!$op){	
			die("에러! operation 객체를 얻을 수 없음");
		}

		// 로그인 한 유 이므로 세션 정보 사용
		$writer = $this->em->getReference('Entity\User', $this->session->userdata('user_id'));

		$log = new Entity\OperationLog;
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


	public function nextStatus($op) {
		if(!$op) {
			die("에러! operation 객체가  없음");
		}

		$op_data = array(
			'status' => $op->getStatus() + 1,
			);

		$this->updateOperation($op, $op_data);
	}

	// 장비 출고 (설치)
	public function deliveryItem($op) {

		$items = $op->getItems();
		foreach($items as $item) {
			$qty = $item->getQtyRequest();

			// 설치 등록 장비 내용 변경
			$item->setQtyComplete($qty);
			$item->setCompleteFlag(TRUE);

			$part = $this->em->getReference('Entity\Part', $item->part->id);
			$stock = $part->getStock($op->office->id);

			// 시리얼장비 내용 변경
			if($item->part_type == '1') {
				$sp = $item->serial_part;
				if($sp) {
					$sp->setValidFlag(FALSE);		// 유효 재고에서 빠짐
					$sp->setPreviousLocation($item->prev_location);
					$sp->setCurrentLocation($op->work_location);
					$sp->setDateModify();
					// 신품일떄 최초 설치일
					if($item->isNew()) {
						$sp->setDateInstall($op->getDateWork());
					}

					$this->em->persist($sp);
				}
			}

			// 설치중 수량 제거 
			$stock->decrease('s200', $qty);

			$this->em->persist($stock);
		}
	}
	
}



