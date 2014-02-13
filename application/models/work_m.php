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
		$qb->select("w")
			->from("\Entity\Operation", "w")
			->where("w.type >= :type")
			->andWhere("w.type < :type2")
			->orderBy("w.id", "DESC");

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
		// return $this->_getOpList(900);
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where('w.type >= 900')
			->orderBy('w.id', 'DESC');

		return $qb->getQuery()->getResult();
	}

	// 교체 업무 목록
	public function getReplaceList() {
		return $this->_getOpList(400);
	}

	// 이동 업무 목록
	public function getMoveList() {
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where('w.type >= 700')
			->andWhere('w.type < 800')
			->orderBy('w.id', 'DESC');

		return $qb->getQuery()->getResult();
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
				$post_data['serial_number'] = '';
				$this->addItem($op, $post_data);
			}
		} else {
			$item = $this->addItem($op, $post_data);
		}

		// 장비 재고량 변경
		$stock = $part->getStock($op->office->id);
		$stock->setQtyS100($stock->qty_s100 + $request_qty);	// 발주 수량 update

		/////////////////
		// 한번에 flush
		/////////////////
		$this->em->flush();

		return $op;
	}

	///////////////
	// 철수 업무 생성 //
	///////////////
	public function createCloseOperation($type, $post) {
		return $this->addOperation($type, $post, TRUE);
	}

	///////////////
	// 업무 메인 생성 
	///////////////
	public function createOperation($type, $post, $do_flush=FALSE) {
		return $this->addOperation($type, $post, $do_flush);
	}

	public function addOperation($type, $post, $do_flush=FALSE) {
		
		// 새로운 업무 객체
		$new = new Entity\Operation;

		$new->setType($post['op_type']);
		$new->setOperationNumber($this->makeOperationNumber());		
		$new->setDateRegister();
		$new->setDateRequest($post['date_request']);
		
		// 생성 시 작업 예정일에 요청일은 기본으로 설정함
		if(!isset($post['date_expect'])) {
			$new->setDateExpect($post['date_request']);
		} else {
			$new->setDateExpect($post['date_expect']);
		}

		// 상태가 지정되어 생성되는 경우
		$new->setStatus( isset($post['status']) ? $post['status'] : '1');

		// 요청 메모
		if(isset($post['memo'])) {
			$new->setMemo($post['memo']);
		}

		// 점포 개점일 or 폐점일
		if(isset($post['date_store'])){
			$new->setDateStore($post['date_store']);
		}

		if(isset($post['date_open']) && ($post['op_type'] >= '200' && $post['op_type'] < '300')) {
			$new->setDateStore($post['date_open']);
		}

		if(isset($post['date_close']) && ($post['op_type'] >= '300' && $post['op_type'] < '400')) {
			$new->setDateStore($post['date_close']);
		}

		// 요청자 (넘어온 데이터에 user_id 가 없다면 세션 사용)
		if(!isset($post['user_id'])) {
			$user = $this->em->getReference('Entity\User', $this->session->userdata('user_id'));
		} else {
			$user = $this->em->getReference('Entity\User', $post['user_id']);
		}
		$new->setUser($user);

		// 담당 사무소
		$office = $this->em->getReference('Entity\Office', $post['office_id']);
		$new->setOffice($office);

		// 입고 업무시 납품처
		if($type >= '100' && $type < '200') {
			$part = $this->em->getReference('Entity\Part', $post['part_id']);
			$new->setWorkLocation(GS2_LOCATION_TYPE_COMPANY, $part->company->id);
		}
		// 설치,철수,교체 는 점포 
		else if( $type >= '200' && $type < '500') {
			$store = $this->em->getReference('Entity\Store', $post['store_id']);
			$new->setWorkLocation(GS2_LOCATION_TYPE_STORE, $store->id);
		}
		// 이동은 사무소
		else if( $type >= '700' && $type < '800') {
			$new->setWorkLocation(GS2_LOCATION_TYPE_OFFICE, $post['target_office_id']);
		}
		// 상태변경은 사무소
		else if( $type >= '900' && $type < '999') {
			$new->setWorkLocation(GS2_LOCATION_TYPE_OFFICE, $post['office_id']);
		}


		$this->em->persist($new);

		/////////////////
		// 시스템 로그 생성
		/////////////////
		$log_data = array(
			'type' 		=> '1',
			'content' 	=> gs2_op_type($type) . ' 요청이 생성',
			'event'		=> '생성'
		);

		$this->addLog($new, $log_data);

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

		// is_complete 변경 (상태변경, 교체 업무에서만 사용)
		if(isset($data['is_complete'])) {
			$op->setCompleteFlag($data['is_complete']);
		}

		$op->setDateModify();

		// save & apply
		$this->em->persist($op);
		if($do_flush) {
			$this->em->flush();
		}

		return $op;
	}

	// 업무 메인 삭제
	public function removeOperation($op, $do_flush = FALSE) {
		// 첨부 파일 있을 경우 먼저 삭제
		if($op->numFiles() > 0) {
			foreach($op->files as $f) {
				$file_path = GS2_UPLOAD_BASEPATH . $f->save_name;
				if(file_exists($file_path)) {
					unlink($file_path);
				}

				// 메인 삭제 시 자동 삭제됨
				// $this->em->remove($f);
			}
		}
		
		// 업무 메인 삭제
		$this->em->remove($op);

		if($do_flush) {
			$this->em->flush();
		}
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

		// 분실장비 수량
		if(isset($data['qty_lost']) && intval($data['qty_lost']) > 0) {
			$item->setQtyLost($data['qty_lost']);
			$item->setQtyRequest(0);		// 분실 장비 처리시 요청 수량은 0
		}

		// 시리얼넘버 장비일 경우
		// - 직전위치 로 검색하는 경우 시리얼넘버가 없는 경우도 있음
		// - 시리얼넘버가 없을 경우는???
		if($part->type == '1') {
			// 등록된 시리얼 장비이면
			if(!empty($data['serial_part_id'])) {
				$sp = $this->part_model->getSerialPart($data['serial_part_id']);
				$item->setSerialPart($sp);
			}
			
			$item->setSerialNumber($data['serial_number']);

			// 설치 - 시리얼장비 상태,flag
			if($op->type >= '200' && $op->type < '300') {
				$sp->setValidFlag(FALSE);
				$sp->setStatus('2');
				$this->em->persist($sp);
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
	public function addLog($op, $data, $do_flush=FALSE) {
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
		$log->setDateRegister();

		// 시스템 로그 시 이벤트 기록
		if($data['type'] == '1' && isset($data['event'])) {
			$log->setEvent($data['event']);
		}

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
			// 설치된 장비는 우리 재고가 아님
			if($item->part_type == '1') {
				$sp = $item->serial_part;
				if($sp) {
					$sp->setPreviousLocation($item->prev_location);
					$sp->setCurrentLocation($op->work_location);
					$sp->setDateModify();
					$sp->setValidFlag(FALSE);		// 유효 재고에서 빠짐
					$sp->setStatus('1');			// 정상설치 상태로 변경
					// 신품일떄 최초 설치일
					if($item->isNew()) {
						$sp->setDateInstall($op->getDateWork());
						$sp->setMemo('');
					}

					$this->em->persist($sp);
				}
			}

			// 설치중 수량 제거 
			$stock->decrease('s200', $qty);

			$this->em->persist($stock);
		}
	}

	// 아이템 내 수량 장비 있는지 검사
	public function checkCountPartInItem($op, $part_id, $is_new) {
		if($op->numItems() == 0) {
			return FALSE;
		}
		
		foreach($op->getItems() as $item) {
			if( $part_id == $item->part->id && $is_new == $item->is_new) {
				return $item;
				break;
			}
		}

		return FALSE;
	}

	// 아이템 내 시리얼 장비 있는지 검사
	public function checkSerialPartInItem($op, $serial_number) {
		if($op->numItems() == 0) {
			return FALSE;
		}

		foreach($op->getItems() as $item) {
			if($item->serial_number == $serial_number) {
				return $item;
				break;
			}
		}
		return FALSE;
	}


	// 로그 얻기
	public function getLogs($op) {
		$repo = $this->em->getRepository('Entity\OperationLog');
		$logs = $repo->findBy(array('operation' => $op), array('id'=>'desc'));

		return $logs;
	}


	/**
	 * gs2_operation_targets 테이블에 데이터 생성
	 * 상태변경, 교체 업무의 대상이 되는 업무 (1-N 관계 정의 함)
	 * 
	 * @param  Entity\Operation  $op       업무
	 * @param  Entity\Operation  $target   대상 업무
	 * @param  boolean $do_flush 
	 * @return Entity\OperationTarget      object
	 */
	public function createTargetOperation($op, $target, $do_flush=FALSE) {
		$top = new Entity\OperationTarget($op, $target);
		$this->em->persist($top);

		if($do_flush) {
			$this->em->flush();
		}

		return $top;
	}

	// 대상 업무 삭제
	public function removeTarget($target, $do_flush = FALSE) {
		$this->em->remove($target);
		
		if($do_flush) {
			$this->em->flush();
		}		
	}
}



