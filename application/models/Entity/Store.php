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

	/** @column(type="string", length=30) */
	protected $code;

	/**
	 * 점포명
	 * 
	 * @Column(type="string", length=50)
	 */
	protected $name;				
	
	/** @Column(type="string", length=30) */
	protected $owner_name;

	/** @Column(type="string", length=20) */
	protected $tel;

	/** @Column(type="string", length=100) */
	protected $address;

	/** @Column(type="string", length=20) */
	protected $tel_rfc;

	/** @Column(type="string", length=20) */
	protected $tel_ofc;

	/** @Column(type="string", length=20) */
	protected $scale;

	/** @Column(type="string", length=1) */
	protected $has_postbox;

	/** @Column(type="string", length=20) */
	protected $join_type;

	/** @Column(type="string", length=1) */
	protected $status;

	/** @Column(type="datetime") */
	protected $date_register;


	public function __get($key) {
		return $this->$key;
	}

	public function __set($key, $val) {
		if($key == 'id') {
			trigger_error("ID는 임의로 지정할 수 없음");
			exit;
		}

		$this->$key = $val;
	}

}

