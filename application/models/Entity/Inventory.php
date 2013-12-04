<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

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
	 * 
	 * @OneToMany(targetEntity="Stock", mappedBy="inventory")
	 */
	protected $stock_list;

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

	public function __construct() {
		$this->stock_list = new ArrayCollection();
	}


	// --------------------------------------------

	/*
	신품 입고
	 */
	
	/**
	 * 장비 입고
	 * @param [type] $type [description]
	 * @param [type] $part [description]
	 * @param [type] $qty  [description]
	 */
	public function in($type, $part, $qty) {

		// 재고 목록 내 $part 장비가 있는지 검색
		$stock = $this->getPartStock($part);

		// 장비가 존재하지 않으면
		if($stock === NULL) {
			$stock = new Stock();		// 생성

			$stock->setInventory($this);
			$stock->setPart($part);
			$stock->setQtyNew(0);
		}

		// 재고 수량 변경
		$stock->setQtyNew($stock->qty_new + $qty);

		return $stock;
	}

	public function add($type, $part, $qty) {
		return $this->in($type, $part, $qty);
	}


	// 장비 출고
	public function out($type, $part, $qty) {
		$stock = $this->getPartStock($part);

		if($stock === NULL) {
			return FALSE;
		} 

		// 출고 수량이 재고 수량 보다 클수 없음 
		if( $qty > $stock->qty_new) {
			trigger_error("출고 수량이 재고 수량 보다 클수 없음");
			return FALSE;
		}

		$stock->setQtyNew($stock->qty_new - $qty);
		return $stock;
	}

	// 창고 내 장비 존재 하는지 검사하여 
	// 있으면 Stock object 를 반환한다
	// 없으면 NULL 	
	public function getPartStock($part) {
		if(!count($this->stock_list))
			return NULL;

		foreach($this->stock_list as $item) {
			if( $part->id == $item->part->id) {
				return $item;			// Stock object
				break;
			}
		}

		return NULL;
	}
}

