<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_COMPANIES")
 */
class Company
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_company_seq")
	 */
	protected $id;				// 트리거로 자동 생성

	/** @column(type="string", length=20) */
	protected $code;

	/** @Column(type="string", length=1) */
	protected $type;
	
	/** @column(type="string", length=50) */
	protected $name;

	/** @column(type="string", length=20) */
	protected $tel;

	/** @column(type="string", length=100) */
	protected $address;

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;
	
	/** @column(type="string", length=255) */
	protected $memo;

	/** @column(type="datetime", nullable=true) */
	protected $date_register;

	/** @Column(type="string", length=1) */
	protected $status;

	public function __get($key) {
		return $this->$key;
	}

	//==========================================

	// 등록일시 얻기
	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}

	public function getUser(){
		return ($this->user) ? $this->user : '';
	}

	// 담당자 - 링크 
	public function getUserAnchor() {
		return ($this->user) ? $this->user->name : '';
	}

	// 담당자
	public function setUser($user) {
		$this->user = $user;
	}

	public function setCode($value='') {
		$this->code = $value;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setName($value='')
	{
		$this->name = $value;
	}

	public function setTel($value='')
	{
		$this->tel = $value;
	}

	public function setAddress($value='')
	{
		$this->address = $value;
	}

	public function setMemo($value='')
	{
		$this->memo = $value;
	}

	public function setStatus($value='')
	{
		$this->status = $value;
	}

	// 등록일시
	public function setDateRegister($date = 'now') {
		if(!empty($date))
			$this->date_register = new \DateTime($date);
	}
}

