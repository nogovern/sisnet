<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_USER_LOGS")
 */
class UserLog
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_user_log_seq")
	 */
	protected $id;

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;

	/** @Column(name="date_register", type="datetime", nullable=true) */
	protected $date_login;

	/** @column(type="string", length=15) */
	protected $ip_address;


	//--------------------------------------------------------

	public function __get($key) {
		return $this->$key;
	}

	public function __set($key, $val) {
		if( $key == 'id') {
			throw new Exception("ID 값은 설정할 수 없습니다", 1);
			return false;
		} elseif( $key == 'date_login') {
			$this->$key = new \DateTime($val);

		} else {
			$this->$key = $val;
		}
	}

	// 이름 반환
	public function getUserName() {
		if($this->user && $this->user instanceof Entity\User) {
			return $this->user->name;
		} else {
			return null;
		}
	}

	// 이름-링크 형태로 반환
	public function getUserAnchor() {

	}

}

