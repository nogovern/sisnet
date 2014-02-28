<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Doctrine\ORM\Tools\Pagination\Paginator;

class Work_m extends MY_Model {
	private $operation;

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Operation');
		$this->setTableName('gs2_operations');
		
		$this->repo = $this->em->getRepository($this->getEntityName());
	}

	///////////
	// 안씀!!
	///////////
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
	 * 입고 목록
	 * 
	 * @return [type] [description]
	 */
	public function getEnterList($criteria = array()) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('Entity\Operation', 'w')
			->where('w.type >= 100')
			->andWhere('w.type < 200')
			->orderBy('w.id', 'DESC');

		// 검색 조건 있을 경우
		if(count($criteria)) {
			foreach($criteria as $key => $val) {
				if($key == '0' || $key == 'all')
					continue;
				if($key == 'status') {
					$qb->andWhere("w.status = $val");
				} 

				if($key == 'type') {
					$qb->andWhere("w.type = $val");
				}

				if($key == 'office' && $val > 0) {
					$qb->andWhere("w.office = $val");
				}
			}
		}

		$rows = $qb->getQuery()->getResult();

		// 장비종류 검색이 있을 경우 
		if(isset($criteria['category']) && $criteria['category'] > 0) {
			$result = array();
			foreach($rows as $op) {
				$part = $op->getItem(0)->part;
				if($part->category->id == $criteria['category']) {
					$result[] = $op;
				}

			}
			return $result;

		} else {
			return $rows;
		}

	}
	///////////////
	// 업무 목록 총등록수 
	////////////////
	public function numRows($type, $criteria=array()) {
		$next_type = $type + 99;

		$qb = $this->em->createQueryBuilder();
		$qb->select('count(w.id)');
		$qb->from('Entity\Operation', 'w')
			->where("w.type >= $type")
			->andWhere("w.type <= $next_type");

		// 올바른 검색키 배열
		$valid_key = array('type', 'office', 'worker', 'status');

		foreach($criteria as $key => $val) {
			$key = ($key == 'all') ? 0 : $key;

			if(!in_array($key, $valid_key)) {
				log_message('error', "$key 는 허용되지 않는 검색 옵션입니다");
			} else {
				if($val > 0) {
					$qb->andWhere("w.$key = $val");
				}
			}
		}

		$query = $qb->getQuery();
		return $query->getSingleScalarResult();

	}

	////////////////
	// 업무 리스트 공통 (입고만 예외)
	////////////////
	public function getOperations($type, $criteria=array(), $limit = 0, $page = 1, $order=array()) {
		$next_type = $type + 99;

		$qb = $this->em->createQueryBuilder();
		$qb->select('w');
		$qb->from('Entity\Operation', 'w')
			->where("w.type >= $type")
			->andWhere("w.type <= $next_type")
			->orderBy('w.id', 'DESC');

		// 올바른 검색키 배열
		$valid_key = array('type', 'office', 'worker', 'status');

		foreach($criteria as $key => $val) {
			$key = ($key == 'all') ? 0 : $key;

			if(!in_array($key, $valid_key)) {
				log_message('error', "$key 는 허용되지 않는 검색 옵션입니다");
			} else {
				if($val > 0) {
					$qb->andWhere("w.$key = $val");
				}
			}
		}

		$offset = ($page - 1) * GS2_LIST_PER_PAGE;
		$query = $qb->setFirstResult($offset)->setMaxResults($limit)->getQuery();
		return $query->getResult();

	}

	/**
	 * 설치 업무 목록
	 * 
	 * @return  array of objects [description]
	 */
	public function getInstallList($criteria = array()) {
		$result = $this->getOperations(GS2_OP_TYPE_INSTALL, $criteria);
		return $result;
	}

	// 철수 목록
	public function getCloseList($criteria = array()) {
		$result = $this->getOperations(GS2_OP_TYPE_CLOSE, $criteria);
		return $result;
	}

	// 상태변경 업무 목록
	public function getChangeList($criteria = array()) {
		$result = $this->getOperations(GS2_OP_TYPE_CHANGE, $criteria);
		return $result;
	}

	// 교체 업무 목록
	public function getReplaceList($criteria = array(), $limit=0, $offset=0) {
		$result = $this->getOperations(GS2_OP_TYPE_REPLACE, $criteria, $limit, $offset);
		return $result;
	}

	// 폐기 업무 목록
	public function getDestroyList($criteria=array()) {
		$result = $this->getOperations(GS2_OP_TYPE_DESTROY, $criteria);
		return $result;
	}

	// 이동 업무 목록
	public function getMoveList($criteria=array()) {
		$result = $this->getOperations(GS2_OP_TYPE_MOVE, $criteria);
		return $result;
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
		// 수리,폐기,이관 은 외부 업체
		else if( ($type >= '500' && $type < '700') || ($type >= '800' && $type < '900')) {
			if(isset($post['company_id'])) {
				$new->setWorkLocation(GS2_LOCATION_TYPE_COMPANY, $post['company_id']);
			}
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

				// 설치 - 시리얼장비 상태,flag
				if($op->type >= '200' && $op->type < '300') {
					$sp->setValidFlag(FALSE);
					$sp->setStatus('2');
					$this->em->persist($sp);
				}
				// 이동업무 시 
				elseif ($op->type == '700') {
					$sp->setValidFlag(FALSE);
					$sp->setStatus('7');
					$this->em->persist($sp);
				}
			}

			$item->setSerialNumber($data['serial_number']);

			////////////////////
			// 직전위치 저장 
			////////////////////

			// 등록되지 않은  시리얼장비인 경우 직전위치를  work_location 로 저장
			$location = isset($sp) ? $sp->getPreviousLocation() : $op->work_location;
			$item->setPreviousLocation($location);
		}

		// 여분 필드 extra 데이터가 있을경우
		if( isset($data['extra']) ) {
			$item->setExtra($data['extra']);
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

		// 스캔 수량
		if(isset($data['qty_scan'])) {
			$item->setQtyScan($data['qty_scan']);
		}

		// 스캔 여부
		if(isset($data['is_scan'])) {
			$item->setScanFlag($data['is_scan']);
		}


		$this->em->persist($item);
		if($do_flush) {
			$this->em->flush();
		}

		return $item;
	}

	// 업무-장비 목록 삭제
	public function removeItem($item, $do_flush = false) {
		$this->em->remove($item);

		if($do_flush) {
			$this->em->flush();
		}
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
					$sp->setPreviousLocation($op->office);
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
		
		// 상태변경, 교체 업무 구분
		if($op->type == '400') {
			$top->setGubun('replace');
		} else if ($op->type == '900') {
			$top->setGubun('change');
		}

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

	// 교체 업무에서 설치, 철수 모두 완료 될시 같이 완료 되도록 함
	public function checkFamily($me) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('t')->from('Entity\OperationTarget', 't')
			->where('t.target = :t_id')
			->andWhere("t.gubun = 'replace' ")
			->setParameter('t_id', $me->id);

		$row = $qb->getQuery()->getSingleResult();

		// 부모 찾기
		$parent = ($row) ? $row->operation : NULL;

		if($parent && $parent->type == '400') {
			// 형제 찾기
			$targets = $parent->getTargets();
			foreach( $targets as $t) {
				if($t->target->id != $me->id)
					$sibling = $t->target;
			}

			// 다른 작업이 이미 '완료' 상태이면 교체 업무을 완료로 변경한다.
			if($sibling->status == '4') {
				$data['status'] 		= '3';
				$data['date_finish']	=  $me->getDateFinish(TRUE);

				$this->updateOperation($parent, $data);

				// 업무 log 생성
				$log_data = array(
					'type' => '1',
					'content' => gs2_op_type($me->type) . ' 완료  =>  교체 업무를 완료 합니다',
					'event'			=> '완료'
				);

				$this->addLog($parent, $log_data);
			}
			return TRUE;
		}
		return FALSE;
	}

	//====================
	// pagination config
	//====================
	public function setPaginationConfig($url = '') {
		
		$this->load->library('pagination');

		$base_url = base_url() . $url;		//변경
		$config = array(
			'base_url' 		=> $base_url,
			'prefix'		=> '',
			'per_page'		=> GS2_LIST_PER_PAGE,
			'uri_segment'	=> 4,
			'num_links'		=> 5,
			'use_page_numbers'	=> TRUE,
			'page_query_string'	=> FALSE
		);

		// 검색 조건이 있을 경우
		if($this->input->get()) {
			$config['suffix'] = '/?' . http_build_query($this->input->get());
			$config['first_url'] = $config['base_url'] . '1' . $config['suffix'];
		}

		return $config;
	}

	/**
	 * 업무 완료  - work/ajax 컨트롤러 위치에서 여기로 이동
	 * 
	 * @param  [type]  $id     [description]
	 * @param  integer $status [description]
	 * @return [type]          [description]
	 */
	public function complete($id, $status = 4, $date_finish = 'now') {
		
		$op = $this->work_model->get($id);
		if(!$op) {
			return false;
		}

		// 추가 모델 로딩
		$this->load->model('part_m', 'part_model');
		$this->load->model('file_m', 'file_model');

		// 첨부파일 저장
		// 2014.02.17 by JKH - ie 첨부파일 에러 때문에 파일 수 검증 추가
		$files = $this->input->post('files');
		if($files && count($files)) {
			foreach($files as $f) {
				$f['gubun'] = '완료';
	    		$f['op_id'] = $op->id;

	    		$this->file_model->create($f);
			}
		}

		// 업무 메인 변경
		$op_data['status'] = $status;
		$op_data['date_finish'] = $date_finish;
		$this->work_model->updateOperation($op, $op_data);

		// 업무 장비 정보 변경

		if( $op->type >= '200' && $op->type < '300') {
			// 장비 출고 후 재고 반영
			$this->work_model->deliveryItem($op);
		} 
		// 철수 후 장비 상태는 점검 전
		elseif ( $op->type >= '300' && $op->type < '400') {
			$items = $op->getItems();

			foreach($items as $item) {
				if($item->part_type == '1') {
					// 기존 시리얼넘버가 존재하면 기존 정보 update
					$sp = ($item->serial_part) ? $this->part_model->getSerialPart($item->serial_part->id): NULL;

					// 없는 시리얼넘버이면 생성
					if(!$sp) 
					{
						$sp_data['part_id'] = $item->part->id;
						$sp_data['previous_location'] = $op->work_location;
						$sp_data['current_location'] = gs2_encode_location($op->office);
						$sp_data['date_enter']	= $op->getDateFinish();				// 최초 설치일
						$sp_data['qty'] = 1;
						$sp_data['serial_number'] = $item->serial_number;
						$sp_data['is_new'] = $item->is_new;
						$sp_data['status'] = '3';
						$sp_data['is_valid'] = 'N';									// 가용 여부

						$sp = $this->part_model->addSerialPart($sp_data, FALSE);
					} 

					else {
						//////////////////////////////////////////////
						// part_m 안에 updateSerialPart 로 구현하자! 
						//////////////////////////////////////////////
						$sp->setPreviousLocation($op->work_location);
						$sp->setCurrentLocation(gs2_encode_location($op->office));
						$sp->setNewFlag(FALSE);
						$sp->setValidFlag(FALSE);
						$sp->setStatus('3');
						$sp->setDateEnter($op->getDateFinish());

					}

					///////////////////////
					// 분실 장비일 경우 처리 
					///////////////////////
					if($item->qty_lost > 0) {
						$sp->setStatus('L');
						$sp->setMemo('분실장비');
					}

					$this->work_model->_add($sp);

					// 새로 생성된 serial_part_id 를 opeartion_parts 테이블에 update
					$item->setSerialPart($sp);
					$this->work_model->_add($item);			
				}

				// 재고수량 변경
				$stock = $item->part->getStock($op->office->id);
				$stock->increase('s900', $item->qty_request);	// 점검 전 수량에 추가
				$this->work_model->_add($stock);
			}
		}

		// 교체 업무 시 부모,형제 업무 파악
		if($op->type == '205' || $op->type == '305') {
			$this->work_model->checkFamily($op);
		}

		// 점포 상태 변경 ( 0-폐점, 1-정상, 2-휴점)
		if($op->type >= '200' && $op->type < '400') {
			$store = gs2_decode_location($op->getWorkLocation());
			
			// 폐점
			if($op->type == '301') {
				$store->setStatus('0');
			}

			// 휴점 
			if($op->type == '303' || $op->type == '304') {
				$store->setStatus('2');
			}

			// 설치
			if($op->type >= '200' && $op->type < '300') {
				$store->setStatus('1');
			}

			$this->em->persist($store);			
		}

		// 업무 log 생성
		$log_data = array(
			'type' => '1',
			'content' => gs2_op_type($op->type) . ' 작업완료 합니다',
			'event'			=> '완료'
		);
		$this->work_model->addLog($op, $log_data, TRUE);
		
		return "업무를 완료하였습니다.";
	}
}



