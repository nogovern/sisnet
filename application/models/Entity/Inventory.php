<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_INVENTORIES")
 */
class Inventory
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_inventory_seq")
	 */
	protected $id;

	/**
	 * @Column(type="string", length=50)
	 */
	protected $name;
	
	/**
	 * @Column(type="string", length=255)
	 */
	protected $description;
	
	/**
	 * @Column(type="string", length=100)
	 */
	protected $address;
	
	/**
	 * @Column(type="string", length=1)
	 */
	protected $status;

	public function __get($key) {
		return $this->$key;
	}

	

}

