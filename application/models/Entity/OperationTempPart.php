<?php
namespace Entity;

/**
 * 작업 처리시 사용되는 장비 정보 임시 저장 테이블 
 * 입고시 - 납품처 배송 시 장비 등록 정보
 * 
 * @Entity
 * @Table(name="GS2_OPERATION_TEMP_PARTS")
 */
class OperationTempPart {
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_temp_seq")
	 */
	protected $id;

	/**
	 * @OneToOne(targetEntity="Operation")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;

	/** @Column(type="string", length=1) */
	protected $part_type;

	/** @Column(type="integer") */
	protected $qty = 0; 

	/** @Column(type="string", length=20) */
	protected $serial_number; 

	/** @Column(type="datetime", nullable=true) */
	protected $date_register;	

	/** @Column(type="string", length=1) */
	protected $is_scan = 'N';

	/** @Column(type="string", length=1) */
	protected $is_complete = 'N';

	// ---------- set -------------
	
	/**
	 * 장비 타입 
	 * @param string $type 장비타입
	 */
	public function setPartType($type) {
		$this->part_type = $type;
	}

	public function setPart($obj) {
		$this->part = $obj;						
	}

	public function setOperation($obj) {
		$this->operation = $obj; 
	}

	public function setUser($obj) {
		$this->user = $obj;
	}

	/**
	 * 수량 등록
	 * 
	 * @param integer $qty 0보다 커야 하며 시리얼장비인 경우 1
	 */
	public function setQuantity($qty) {
		if(!is_numeric($qty) || $qty <= 0){
			die('수량은 1과 같거나 커야함!');
		}
		$this->qty = $qty;
	}

	/**
	 * 시리얼넘버 셋팅 
	 * @param string $no [description]
	 */
	public function setSerialNumber($no) {
		if(empty($no)) {
			return FALSE;
		}

		$this->setQuantity(1);
		$this->serial_number = $no;
	}

	/**
	 * 등록일시
	 * @param string $when 		디폴트는 현재 시각
	 */
	public function setDateRegister($when='now') {
		$this->date_register = new \DateTime($when);
	}

	public function setScanFlag($is_scan) {
		$this->is_scan = ($is_scan) ? 'Y' : 'N';
	}

	public function setCompleteFlag($is_complete) {
		$this->is_complete = ($is_complete) ? 'Y' : 'N';
	}

	// ---------- get -------------
	public function __get($key) {
		return $this->$key;
	}

	public function getSerialNumber() {
		return $this->serial_number;
	}

	public function getDateRegister() {
		return ($this->date_register) ? $this->date_register->formate("Y-m-d H:i:s") : '';
	}

	public function isScan() {
		return ($this->is_scan == 'Y') ? TRUE : FALSE;
	}

	public function isComplete() {
		return ($this->is_complete == 'Y') ? TRUE : FALSE;
	}

	
}