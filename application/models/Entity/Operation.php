<?php
/*
작업 (master)
 */
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="GS2_OPERATIONS")
 */
class Operation
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_op_seq")
	 */
	protected $id;

	/** @Column(type="string", length=20, name="operation_no") */
	protected $operation_number;							// 작업 번호 

	/** @Column(type="string", length=20) */
	protected $type;
	
	/**
	 * @OneToOne(targetEntity="Office")
	 * @JoinColumn(name="office_id", referencedColumnName="id")
	 */
	protected $office;

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;					// 등록 유저

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="worker_id", referencedColumnName="id")
	 */
	protected $worker;					// 담당 유저

	/** @Column(name="work_location", type="string", length=20) */
	protected $work_location;			// 대상 장소 ( office  or company or store) 

	/** @Column(type="datetime", nullable=true) */
	protected $date_register;	// 요청서 등록일시

	/** @Column(type="datetime") */
	protected $date_request;	// 작업 요청일

	/** @Column(type="datetime") */
	protected $date_modify;		// 작업 상태 변경일시

	/** @Column(type="datetime") */
	protected $date_work;		// 작업일시

	/** @Column(type="datetime") */
	protected $date_finish;		// 작업완료일시

	/** @Column(type="datetime") */
	protected $date_expect;		// 작업예정일시

	/** @Column(type="datetime") */
	protected $date_store;		// 점포 계점일 or 폐점일

	/** @Column(type="string", length=4000) */
	protected $memo;

	/** @Column(type="string", length=1) */
	protected $status = '1';

	/** @Column(type="string", length=1) */
	protected $is_complete = 'N'; 

	/**
	 * @OneToMany(targetEntity="OperationPart", mappedBy="operation")
	 */
	protected $items;					// 작업 장비들 목록

	/**
	 * @OneToMany(targetEntity="OperationLog", mappedBy="operation")
	 */
	protected $logs;

	/**
	 * @OneToMany(targetEntity="OperationTarget", mappedBy="operation")
	 */
	protected $targets;

	/**
	 * @OneToMany(targetEntity="OperationFile", mappedBy="operation")
	 */
	protected $files;			// 첨부파일

	//////////
	// 생성자 //
	//////////
	public function __construct() {
		$this->items = new ArrayCollection();
		$this->logs = new ArrayCollection(); 
		$this->targets = new ArrayCollection(); 
		$this->files = new ArrayCollection(); 
	}

	// 아이템 수
	public function numItems() {
		return count($this->items);
	}

	// 파일 수
	public function numFiles() {
		return count($this->files);
	}

	// 대상 작업 수
	public function numTargets() {
		return count($this->targets);
	}

	// ---------- set -------------
	public function setOperationNumber($no) {
		$this->operation_number = $no;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setOffice(Office $ref) {
		$this->office = $ref;
	}

	// 요청자 지정 (필수)
	public function setUser(User $ref) {
		$this->user = $ref;
	}

	// 담당자 지정
	public function setWorker(User $ref) {
		$this->worker = $ref;
	}

	public function setWorkLocation($type, $location_id) {
		$this->work_location = $type . '@' . $location_id;
	}

	// 등록일시
	public function setDateRegister($date = 'now') {
		if(!empty($date))
			$this->date_register = new \DateTime($date);
	}

	// 업무 요청 일시
	public function setDateRequest($date = null) {
		$this->date_request = new \DateTime($date);
	}
	// 변경일시
	public function setDateModify($date = 'now') {
		if(!empty($date))
			$this->date_modify = new \DateTime($date);
	}


	public function setDateWork($date = 'now') {
		if(!empty($date))
			$this->date_work = new \DateTime($date);
	}

	public function setDateFinish($date = 'now') {
		if(!empty($date))
			$this->date_finish = new \DateTime($date);
	}

	// 방문(작업) 예정일시
	public function setDateExpect($date = 'now') {
		if(!empty($date))
			$this->date_expect = new \DateTime($date);
	}

	// 점포 걔폐점일
	public function setDateStore($date = 'now') {
		if(!empty($date))
			$this->date_store = new \DateTime($date);
	}

	public function setMemo($memo) {
		$this->memo = $memo;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setCompleteFlag($value=FALSE) {
		$this->is_complete = ($value) ? 'Y' : 'N';
	}

	// ---------- get -------------
	public function __get($key) {
		return $this->$key;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}

	// 작업 요청일 (= 입고예정일)
	public function getDateRequest($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_request) ? $this->date_request->format($format) : '';
	}

	public function getDateModify($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_modify) ? $this->date_modify->format($format) : '';
	}

	// 작업예정일, 작업일
	public function getDateWork($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_work) ? $this->date_work->format($format) : '';
	}

	public function getDateFinish($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_finish) ? $this->date_finish->format($format) : '';
	}

	public function getDateExpect($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_expect) ? $this->date_expect->format($format) : '';
	}

	public function getDateStore($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_store) ? $this->date_store->format($format) : '';
	}

	public function getWorkLocation() {
		return $this->work_location;
	}

	// 대상 작업 목록
	public function getTargets() {
		return $this->targets;
	}

	// deprecated 업무 아이템(장비) 목록
	public function getItemList() {
		return $this->items;
	}

	// 업무 장비 목록 반환
	public function getItems() {
		// $iterator = $this->items->getIterator();
		$arr = $this->items->toArray();

		// 타입별 모델별 sort
		uasort($arr, function($first, $second) {
			$a = (int)$first->part_type;
			$b = (int)$second->part_type;

			if($a == $b){
				$a1 = $first->part->id;
				$b1 = $second->part->id;

				if($a1 == $b1) return 0;
				return ($a1 < $b1) ? -1: 1;
			}
			return ($a < $b) ? -1 : 1;
		});

		return $arr;
	}

	// 업무 관련 아이템 index 로 1개 얻기, default = 0
	public function getItem($index  = 0) {
		return $this->items[$index];
	}

	// 업무 아이템 추가
	public function addItem($item) {
		$this->items[] = $item;				// OperationPart
	}

	public function getWorker() {
		return $this->worker;
	}

	public function getWorkerInfo() {
		return ($this->worker) ? $this->worker->name : '';
	}

	// 총 요청 수량 or 총 등록 수량
	public function getTotalRequestQty() {
		if(!count($this->items)) {
			return 0;
		} 

		$total = 0;
		foreach($this->items as $item) {
			$total += $item->qty_request;
		}
		return $total;
	}

	// 총 완료 수량
	public function getTotalCompleteQty() {
		if(!count($this->items)) {
			return 0;
		} 

		$total = 0;
		foreach($this->items as $item) {
			$total += $item->qty_complete;
		}
		return $total;
	}

	// 총 스캔 수량 (입고에서만 사용???)
	public function getTotalScanQty() {
		if(!count($this->items)) {
			return 0;
		} 

		$total = 0;
		foreach($this->items as $item) {
			$total += $item->qty_scan;
		}
		return $total;
	}

	// log 리스트를 반환
	public function getLogs() {
		return $this->logs;
	}
}

