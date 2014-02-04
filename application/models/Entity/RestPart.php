<?php
namespace Entity;

/**
 *	휴점 보관 장비
 * 
 * @Entity
 * @Table(name="GS2_STORE_REST_PARTS")
 */
class RestPart
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_rest_seq")
	 */
	protected $id;				// 트리거로 자동 생성

	/**
	 * @OneToOne(targetEntity="Operation")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/**
	 * @OneToOne(targetEntity="Store")
	 * @JoinColumn(name="store_id", referencedColumnName="id")
	 */
	protected $store;

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
	protected $hujum_type;
	
	/** @Column(type="string", length=1) */
	protected $is_install = 'N';

	/** @column(type="datetime", nullable=true) */
	protected $date_register;

	//==========================================
	public function __get($key) {
		return $this->$key;
	}

	public function getOperation() {
		return $this->operation;
	}

	public function getStore() {
		return $this->store;
	}

	public function getPart() {
		return $this->part;
	}

	// 휴점 장비 재설치 여부
	public function isInstall() {
		return ($this->is_install == 'Y') ? TRUE : FALSE;
	}

	// 등록일시 얻기
	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}

	///////////
	// set  //
	///////////
	public function setOperation($obj) {
		$this->operation = $obj;
	}

	public function setStore($obj) {
		$this->store = $obj;
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

	public function setHujumType($value='C')
	{
		$this->hujum_type = $value;
	}

	public function setInstallFlag($flag=FALSE)
	{
		$this->is_install = ($flag) ? 'Y' : 'N';
	}

	// 등록일시
	public function setDateRegister($date = 'now') {
		if(!empty($date))
			$this->date_register = new \DateTime($date);
	}
}

