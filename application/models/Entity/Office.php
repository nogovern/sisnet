<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_OFFICES")
 */
class Office
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_office_seq")
	 */
	protected $id;

	/** @column(type="string", length=20) */
	protected $name;
	
	/** @column(type="string", length=20) */
	protected $code;

	/**
	 * @Column(type="string", length=1)
	 */
	protected $has_inventory = 'N';					// default 값

	/**
	 * @OneToOne(targetEntity="Inventory")
	 * @JoinColumn(name="inventory_id", referencedColumnName="id")
	 */
	protected $inventory;

	/** @Column(type="string", length=20) */
	protected $tel;

	/** @Column(type="string", length=100) */
	protected $address;
	
	/** @Column(type="string", length=255) */
	protected $memo;

	/** @Column(type="string", length=1) */
	protected $status;

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;

	public function __construct(){
		;
	}

	public function __get($key) {
		return $this->$key;
	}

	public function setInventory($instance = NULL) {
		if(is_null($instance)){
			return FALSE;
		}

		$this->inventory = $instance;
		$this->has_inventory = 'Y';
	}

	public function setUser($instance = NULL) {
		if(is_null($instance)){
			return FALSE;
		}
		$this->user = $instance;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function setMemo($memo) {
		$this->memo = $memo;
	}

	public function setTel($tel) {
		$this->tel = $tel;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

}

