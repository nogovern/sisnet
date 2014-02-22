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
	
	/** @Column(type="string", length=1) */
	protected $part_type;
	
	/** @Column(type="integer") */
	protected $qty = 1;
	
	/** @column(type="string", length=30) */
	protected $serial_number;

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

	public function getQty() {
		return $this->qty;
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

	///////////
	// set  //
	///////////

	// D-폐기, R-수
	public function setGubun($val) {
		$this->gubun = $val;
	}

	public function setOperation($obj) {
		$this->operation = $obj;
	}

	public function setSerialPart($obj) {
		$this->serial_part = $obj;
	}

	public function setPart($obj) {
		$this->part = $obj;
	}

	public function setPartType($value='') {
		$this->part_type = $value;
	}

	public function setSerialNumber($value) {
		$this->serial_number = $value;
	}

	public function setQty($value=0)
	{
		$this->qty = $value;
	}

	public function setStatus($value)
	{
		$this->status = $value;
	}

	// 등록일시
	public function setDateRegister($date = 'now') {
		if(!empty($date))
			$this->date_register = new \DateTime($date);
	}
}

