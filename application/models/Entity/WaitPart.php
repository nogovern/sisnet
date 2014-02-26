<?php
namespace Entity;

/**
 *	페기,수리 대기 장비
 * 
 * @Entity
 * @Table(name="GS2_DEPRECATED_PARTS")
 */
class WaitPart
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_part_wait_seq")
	 */
	protected $id;				// 트리거로 자동 생성

	/**
	 * @OneToOne(targetEntity="Operation")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/** @Column(type="string", length=1) */
	protected $gubun;
	
	/**
	 * @OneToOne(targetEntity="SerialPart")
	 * @JoinColumn(name="part_serial_id", referencedColumnName="id")
	 */
	
	protected $serial_part;

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;

	/**
	 * @OneToOne(targetEntity="Office")
	 * @JoinColumn(name="office_id", referencedColumnName="id")
	 */
	protected $office;
	
	/** @Column(type="string", length=1) */
	protected $part_type;
	
	/** @Column(type="integer") */
	protected $qty = 0;						// 등록 수량
	
	/** @Column(type="integer") */
	protected $qty_accept = 0;				// 승인 수량
	
	/** @column(type="string", length=30) */
	protected $serial_number;

	/** @column(type="string", length=20) */
	protected $previous_location;

	/** @Column(type="string", length=1) */
	protected $status = '1';
	
	/** @column(type="datetime", nullable=true) */
	protected $date_register;

	//==========================================
	public function __get($key) {
		return $this->$key;
	}

	public function getOperation() {
		return $this->operation;
	}

	public function getSerialPart() {
		return $this->serial_part;
	}

	public function getPart() {
		return $this->part;
	}

	// 등록수량
	public function getQty() {
		return $this->qty;
	}

	// 승인수량
	public function getQtyAccept() {
		return $this->qty;
	}

	// 폐기, 수리 등록 된 총 수량
	// 수량장비 경우에 유효
	public function getTotal() {
		return $this->qty + $this->qty_accept;
	}

	// 1-대기, 2- 등록, 3-완료
	public function getStatus() {
		return $this->status;
	}

	// 등록일시 얻기
	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}

	public function getSerialNumber() {
		return $this->serial_number;
	}

	public function getPreviousLocation() {
		return $this->previous_location;
	}

	///////////
	// set  //
	///////////

	// D-폐기, R-수리
	public function setGubun($val) {
		$this->gubun = $val;
	}

	public function setOperation($obj) {
		$this->operation = $obj;
	}

	public function setPart($obj) {
		$this->part = $obj;
	}

	public function setPartType($value='') {
		$this->part_type = $value;
	}

	// 시리얼장비 ID
	public function setSerialPart($obj) {
		$this->serial_part = $obj;
	}

	// 시리얼넘버
	public function setSerialNumber($value) {
		$this->serial_number = $value;
	}

	// 직전위치
	public function setPreviousLocation($value) {
		$this->previous_location = $value;
	}

	// 수량
	public function setQty($value=0) {
		$this->qty = $value;
	}

	public function setQtyAccpet($value=0) {
		$this->qty_accept = $value;
	}

	// 상태 (1-대기, 2-등록, 3-완료)
	public function setStatus($value) {
		$this->status = $value;
	}

	// 등록일시
	public function setDateRegister($date = 'now') {
		if(!empty($date))
			$this->date_register = new \DateTime($date);
	}

	// ====== custom method ======
	
	// 1 - 등록수량 , 2 - 승인수량
	public function add($qty, $gubun = 1) {
		if($gubun == 1) {
			return $this->qty += $qty;
		} else {
			return $this->qty_accept += $qty;
		}
	} 

	// 빼기
	public function minus($qty, $gubun = 1) {
		if($gubun == 1) {
			return $this->qty -= $qty;
		} else {
			return $this->qty_accept -= $qty;
		}
	} 
	
	// 더하기 alias	
	public function increase($qty, $gubun = 1) {
		$this->add($qty, $gubun);
	}
	
	// 빼기 alias
	public function decrease($qty, $gubun = 1) {
		$this->minus($qty, $gubun);
	}
	
}

