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
	 * @SequenceGenerator(sequenceName="gs2_operation_part_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="part_list")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/** @Column(type="string", length=1) */
	protected $type;

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part; 

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
	protected $is_complete = 'N';

	/** @Column(type="string", length=1) */
	protected $status = "1";

	/** @Column(type="string", length=255) */
	protected $extra = '';			// 여분 데이타

	// ---------- set -------------
	
	public function __set($key, $value) {
		$this->$key = $value;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setPart($part) {
		$this->part = $part;				// Part Instance
	}

	public function setOperation($op) {
		$this->operation = $op;				// Operatino Instance
	}

	public function setQtyRequest($qty) {
		$this->qty_request = $qty;
	}

	public function setQtyComplete($qty) {
		$this->qty_complete = $qty;
	}

	// 등록일시
	public function setDateRegister() {
		$this->date_register = new \DateTime($when);
	}

	public function setDateModify($when = 'now') {
		$this->date_modify = new \DateTime($when);
	}

	public function setNewFlag($is_new) {
		$this->is_new = ($is_new) ? 'Y' : 'N';
	}

	public function setCompleteFlag($is_complete) {
		$this->is_complete = ($is_complete) ? 'Y' : 'N';
	}

	public function setStatus($status) {
		$this->status = $status;
	}

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

	
}