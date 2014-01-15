<?php
namespace Entity;

/**
 *  @Entity
 *  @Table(name="GS2_USERS")
 */
class User {
	const STATUS_DISABLE 	= 0;
	const STATUS_ENABLE 	= 1;

	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
 	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_user_seq")
	 */
	protected $id;

	/** @Column(type="string", length=1) */
	protected $type;
	
	/** @Column(type="string") */
	protected $username;
	
	/** @Column(type="string") */
	protected $name;

	/** @Column(type="string") */ 
	protected $password;
	
	/** @Column(type="string", length=20) */
	protected $phone;
	
	/** @Column(type="string") */ 
	protected $email;
	
	/** @Column(type="string", length=20) */
	protected $fax;

	/** @Column(type="datetime") */ 
	protected $date_register;
	
	/** @Column(type="string", length=1) */
	protected $status;

	/**
	 * @ManyToOne(targetEntity="Office")
	 * @JoinColumn(name="office_id", referencedColumnName="id")
	 */
	protected $office;			// 사무소 연관

	/**
	 * @ManyToOne(targetEntity="Company")
	 * @JoinColumn(name="company_id", referencedColumnName="id")
	 */
	protected $company;			// 거래처 연관
	//==============================================================

	public function __get($key) {
		return $this->$key;
	}

	public function getName() {
		return $this->name;
	}

	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}

	// 회원 타입 문자열
	public function getUserTypeText() {
		return ($this->type) ? constant('GS2_USER_TYPE_' . $this->type) : '';
	}


	public function setType($val) {
		$this->type = $val;
	}

	public function setUsername($name) {
		$this->username = $name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setPassword($string) {
		$this->password = $string;
	}

	// 연락처
	public function setPhone($value='')
	{
		$this->phone = $value;
	}

	public function setEmail($value='')
	{
		$this->email = $value;
	}

	// 등록일시 
	public function setDateRegister() {
		$this->date_register = new \DateTime();		// namespace 주의
	}

	public function setStatus($val) {
		$this->status = $val;
	}

	public function setCompany(Company $reference) {
		$this->company = $reference;
	}

	public function setOffice(Office $reference) {
		$this->office = $reference;
	}

}