<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_STORES")
 */
class Store
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_store_seq")
	 */
	protected $id;

	/** @column(type="string", length=20) */
	protected $code;	// 점포 코드

	/** @column(type="string", length=20) */
	protected $code2;	// 점포 가변코드

	/**
	 * 점포명 @Column(type="string", length=50, nullable=false) */
	protected $name;				
	
	/** @Column(type="string", length=30) */
	protected $owner_name;

	/** @Column(type="string", length=20) */
	protected $owner_tel;

	/** @Column(type="string", length=20) */
	protected $tel;

	/** @Column(type="string", length=100) */
	protected $address;

	/** @Column(type="string", length=30) */
	protected $rfc_name;

	/** @Column(type="string", length=20) */
	protected $rfc_tel;

	/** @Column(type="string", length=20) */
	protected $ofc_tel;

	/** @Column(type="string", length=30) */
	protected $ofc_name;

	/** @Column(type="string", length=1) */
	protected $has_postbox;

	/** @Column(type="string", length=20) */
	protected $join_type;

	/** @Column(type="string", length=1) */
	protected $status;

	/** @Column(type="datetime") */
	protected $date_register;

	// ==================================================================

	public function __get($key) {
		return $this->$key;
	}

	public function __set($key, $val) {
		$invalid_keys = array('id', 'date_register');
		
		if(in_array($key, $invalid_keys)) {
			trigger_error($key . " 는 setter 메서드를 사용하세요");
		} else {
			$this->$key = $val;
		}
	}

	// 등록일시
	public function setDateRegister($when = 'now') {
		$this->date_register = new \DateTime($when);
	}

	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}

}

