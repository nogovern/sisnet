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
	protected $user;						// 등록 유저

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="worker_id", referencedColumnName="id")
	 */
	protected $worker;					// 담당 유저

	/** @Column(name="work_location", type="string", length=20) */
	protected $work_location;				// 대상 장소 ( office  or company or store) 

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

	/** @Column(type="string", length=255) */
	protected $memo;

	/** @Column(type="string", length=1) */
	protected $status = '1';

	/** @Column(type="string", length=1) */
	protected $is_complete = 'N'; 

	/**
	 * @OneToMany(targetEntity="OperationPart", mappedBy="operation")
	 */
	private $items;					// 작업 장비들 목록

	//////////
	// 생성자 //
	//////////
	public function __construct() {
		$this->items = new ArrayCollection();
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

	// 변경일시
	public function setDateModify($date = 'now') {
		if(!empty($date))
			$this->date_modify = new \DateTime($date);
	}

	// 업무 요청 일시
	public function setDateRequest($date = 'now') {
		if(!empty($date))
			$this->date_request = new \DateTime($date);
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

	// 업무 아이템(장비) 목록 
	public function getItemList() {
		return $this->items;
	}

	public function getItems() {
		return $this->items;
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

		$qty = 0;
		foreach($this->items as $item) {
			$qty += $item->qty_request;
		}
		return $qty;
	}

	// 총 완료 수량
	public function getTotalCompleteQty() {
		if(!count($this->items)) {
			return 0;
		} 

		$qty = 0;
		foreach($this->items as $item) {
			$qty += $item->qty_complete;
		}
		return $qty;
	}

	// 총 스캔 수량 (입고에서만 사용???)
	public function getTotalScanQty() {
		if(!count($this->items)) {
			return 0;
		} 

		$qty = 0;
		foreach($this->items as $item) {
			$qty += $item->qty_scan;
		}
		return $qty;
	}
}

