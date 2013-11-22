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
	 * @SequenceGenerator(sequenceName="user_id_seq")
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
	protected $date_register;
	protected $status;

	public function __construct() {

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
}