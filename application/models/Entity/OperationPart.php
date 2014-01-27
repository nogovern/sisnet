<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_OPERATION_PARTS")
 */
class OperationPart {
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_op_part_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="itmes")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;

	/**
	 * 시리얼관리장비
	 * 
	 * @OneToOne(targetEntity="SerialPart")
	 * @JoinColumn(name="part_serial_id", referencedColumnName="id")
	 */
	protected $serial_part;  

	/** @Column(type="integer") */
	protected $qty_request = 0; 

	/** @Column(type="integer") */
	protected $qty_complete = 0; 

	/** @Column(type="datetime", nullable=true) */
	protected $date_register;	

	/** @Column(type="datetime", nullable=true) */
	protected $date_modify;

	/** @Column(type="string", length=1) */
	protected $is_new = 'Y';

	/** @Column(type="string", length=1) */
	protected $is_complete = 'N';		// 검검 대기를 위한 필드

	/** @Column(type="string", length=1) */
	protected $status = "1";

	/** @Column(type="string", length=255) */
	protected $extra;			// 여분 데이타

	//---- 2014.1.16 에 추가된 필드

	/** @Column(type="string", length=10) */
	protected $prev_location;

	/** @Column(type="string", length=20) */
	protected $serial_number = "";

	/** @Column(type="string", length=1) */
	protected $is_scan = "N";

	/** @Column(type="integer") */
	protected $qty_lost = 0;

	/** @Column(type="string", length=30) */
	protected $part_name;

	/** @Column(type="string", length=1) */
	protected $part_type;

	/** @Column(type="integer") */
	protected $qty_scan = 0;

	// ---------- get -------------
	public function __get($key) {
		return $this->$key;
	}

	public function isNew() {
		return ($this->is_new == 'Y') ? TRUE : FALSE;
	}

	public function isComplete() {
		return ($this->is_complete == 'Y') ? TRUE : FALSE;
	}

	public function getQtyRequest(){
		return $this->qty_request;
	}

	public function getQtyComplete() {
		return $this->qty_complete;
	}

	public function getQtyScan() {
		return $this->qty_scan;
	}

	public function isScan()
	{
		return ($this->is_scan == 'Y') ? TRUE : FALSE;
	}

	// ---------- set -------------
	
	public function __set($key, $value) {
		$this->$key = $value;
	}

	public function setOperation($op) {
		$this->operation = $op;				// Operatino Instance
	}

	// 장비
	public function setPart($part) {
		$this->part = $part;				// Part Instance
	}
	
	// 시리얼관리장비
	public function setSerialPart($obj) {
		$this->serial_part = $obj;
	}	

	public function setQtyRequest($qty) {
		$this->qty_request = $qty;
	}

	public function setQtyComplete($qty) {
		$this->qty_complete = $qty;
	}

	// 등록일시
	public function setDateRegister($when = 'now') {
		$this->date_register = new \DateTime($when);
	}

	public function setDateModify($when = 'now') {
		$this->date_modify = new \DateTime($when);
	}

	public function setNewFlag($is_new=TRUE) {
		$this->is_new = ($is_new) ? 'Y' : 'N';
	}

	public function setCompleteFlag($is_complete=TRUE) {
		$this->is_complete = ($is_complete) ? 'Y' : 'N';
	}

	public function setStatus($status) {
		$this->status = $status;
	}

	public function setSerialNumber($value='')
	{
		$this->serial_number = $value;
	}

	public function setPreviousLocation($value='')
	{
		$this->prev_location = $value;
	}

	// 입고 시 장비 스캔 여부
	public function setScanFlag($value)
	{
		$this->is_scan = ($value == TRUE) ? 'Y' : 'N';
	}

	// 분실 수량
	public function setQtyLost($value)
	{
		$this->qty_lost = $value;
	}

	// 스캔한 장비 수량
	public function setQtyScan($value)
	{
		$this->qty_scan = $value;
	}

	// part_type, part_name 은 작업 등록 시점의 기록 유지 위해 추가
	// 장비명
	public function setPartName($value='')
	{
		$this->part_name = $value;
	}

	// 장비 종류
	public function setPartType($value='')
	{
		$this->part_type = $value;
	}
	
}