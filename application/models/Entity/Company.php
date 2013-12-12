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

	public function __set($key, $value) {
		$this->$key = $value;
	}

	public function getRegisterDate() {
		return ($this->date_register) ? $this->date_register->format('Y-m-d H:i:s') : '';
	}

	public function setUser(User $user) {
		$this->user = $user;
	}

	
}
