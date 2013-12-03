<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="GS2_INVENTORY_PART")
 */
class Stock
{
	const QTY_TYPE_NEW 		= 'new';
	const QTY_TYPE_USED 	= 'used';
	const QTY_TYPE_S100 	= 's100';
	const QTY_TYPE_S200 	= 's200';
	const QTY_TYPE_S300 	= 's300';
	const QTY_TYPE_S400 	= 's400';
	
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
	protected $inventory;

	/**
	 * @ManyToOne(targetEntity="Part", inversedBy="stock_list")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;

	/** @Column(type="integer") */
	protected $qty_minimum;

	/** @Column(type="integer") */
	protected $qty_new;

	/** @Column(type="integer") */
	protected $qty_used;

	/** @Column(type="integer") */
	protected $qty_s100;

	/** @Column(type="integer") */
	protected $qty_s400;

	public function __construct()
	{

	}
	
	/**
	 * 매직 메소드 (테스트용)
	 */
	public function __get($key) {
		return $this->$key;
	}

	public function setInventory(Inventory $inven) {
		$this->inventory = $inven;

		return $this;
	}

	public function setPart(Part $part) {
		$this->part = $part;

		return $this;
	}

	// 기준 수량  (최소 수량)
	public function setQtyMinimum($qty) {
		$this->qty_minimum = $qty;
	}

	public function addQtyMinimum($qty) {
		$this->qty_minimum = $this->qty_minimum + $qty;
	}

	public function substractQtyMinimum($qty) {
		if( $qty > $this->qty_minium) {
			throw new Exception("재고량 보다 큰 수량을 뺄 수 없습니다", 1);
		}
		$this->qty_minimum = $this->qty_minimum - $qty;
	}

	// 신품 수량  
	public function setQtyNew($qty) {
		$this->qty_new = $qty;

	}

	// 중고 수량  
	public function setQtyUsed($qty) {
		$this->qty_used = $qty;
	}

	//////////////////
	// 비가용 수량 //
	//////////////////
	public function setQtyS100($qty) {
		$this->qty_s100 = $qty;
	}

	public function setQtyS400($qty) {
		$this->qty_s400 = $qty;
	}

	
}

