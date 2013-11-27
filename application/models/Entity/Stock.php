<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="GS2_INVENTORY_PART")
 */
class Stock
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_stock_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Inventory", inversedBy="stock_list")
	 * @JoinColumn(name="inventory_id", referencedColumnName="id")
	 */
	protected $inventories;

	/**
	 * @ManyToOne(targetEntity="Part", inversedBy="stock_list")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $parts;

	/** @Column(type="integer") */
	protected $qty_minimum;

	/** @Column(type="integer") */
	protected $qty_new;

	/** @Column(type="integer") */
	protected $qty_used;

	public function __construct()
	{
		$this->inventories = new ArrayCollection();
		$this->part = new ArrayCollection();
	}
	
	/**
	 * 
	 */
	public function __get($key) {
		return $this->$key;
	}

}

