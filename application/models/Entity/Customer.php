<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_CUSTOMERS")
 */
class Customer
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_customer_seq")
	 */
	protected $id;

	/**
	 * @column(type="string", length=20)
	 */
	protected $code;

	/**
	 * @Column(type="string", length=1)
	 */
	protected $type;
	
	/**
	 * @Column(type="string")
	 */
	protected $name;

	protected $tel;
	protected $address;
	protected $user_id;
	protected $memo;

	/**
	 * @Column(type="datetime", nullable=true)
	 */
	protected $date_register;
	protected $status;

	public function __get($key) {
		return $this->$key;
	}

	
}

