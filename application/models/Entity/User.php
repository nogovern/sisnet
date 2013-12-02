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

	/**
	 * @Column(type="string", length=1)
	 */
	protected $type;
	
	/**
	 * @Column(type="string")
	 */
	protected $username;

	/**
	 * @Column(type="string")
	 */
	protected $name;

	/**
	 * @Column(type="string")
	 */
	protected $password;

	/**
	 * @Column(type="string")
	 */
	protected $phone;

	/**
	 * @Column(type="string")
	 */
	protected $email;

	/**
	 * @Column(type="string")
	 */
	protected $fax;

	/**
	 * @Column(type="datetime")
	 */
	protected $date_register;

	/**
	 * @Column(type="string", length=1)
	 */
	protected $status;
	
	/**
	 * [__get description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function __get($key) {
		return $this->$key;
	}

	public function setType($val) {
		$this->type = $val;
	}

	public function setUsername($name) {
		$this->username = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setPassword($string) {
		$this->password = $string;
	}

	// 등록일시 
	public function setDateRegister() {
		$this->date_register = new \DateTime();		// namespace 주의
	}

	public function setStatus($val) {
		$this->status = $val;
	}

	// datetime 타입을 문자열로 반환
	// (주의) datetime 객체 타입의 변수는 직접 접근할 수 없다. (not public) 
	public function getDateRegister() {
		$temp = $this->date_register;

		return (is_object($temp)) ? $temp->format('Y-m-d H:i:d') : NULL;
	}

	public function getUserTypeText($type='') {
		/*
		  임시 전역변수 선언
		 */
		$_var['user_type'] = array(
		  1  =>  '시스네트',
		  2  =>  'GS25',
		  3  =>  '납품처',
		);

		if(empty($this->type) || !array_key_exists($this->type, $_var['user_type']))
			return '';

		return $_var['user_type'][$this->type];
	}
}