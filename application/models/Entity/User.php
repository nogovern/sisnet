<?php
namespace Entity;

/**
 *  @Entity
 *  @Table(name="GS2_USERS")
 */
class User {
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
 	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_user_seq")
	 */
	protected $id;

	protected $gubun;
	
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

	protected $phone;
	protected $email;
	protected $fax;

	/**
	 * @Column(type="datetime")
	 */
	protected $date_register;
	protected $status;
	
	/**
	 * [__get description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function __get($key) {
		return $this->$key;
	}

	public function setUserName($name) {
		$this->username = $name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setPassword($string) {
		$this->password = $string;
	}

	// datetime 타입을 문자열로 반환
	// (주의) datetime 객체 타입의 변수는 직접 접근할 수 없다. (not public) 
	public function getDateRegister() {
		$temp = $this->date_register;

		return (is_object($temp)) ? $temp->format('Y-m-d H:i:d') : NULL;
	}
}