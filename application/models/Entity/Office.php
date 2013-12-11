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

	/**
	 * @column(type="string", length=20)
	 */
	protected $code;

	/**
	 * @Column(type="string", length=50)
	 */
	protected $name;
	
	/**
	 * @Column(type="string", length=1)
	 */
	protected $has_inventory;

	/**
	 * @Column(type="integer")
	 */
	protected $inventory_id;

	/**
	 * @Column(type="string", length=20)
	 */
	protected $tel;

	/**
	 * @Column(type="string", length=100)
	 */
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


	public function __get($key) {
		return $this->$key;
	}


}

