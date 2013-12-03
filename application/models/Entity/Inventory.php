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
	public function add($type, $part, $qty) {
		$is_found = FALSE;

		// 재고 목록 내 $part 장비가 있는지 검색
		foreach($this->stock_list as $item) {
			// 창고내 장비가 존재하면
			if( $part->id == $item->part->id) {
				$is_found = TRUE;
				$stock = $item;
				break;
			}
		}

		// 장비가 존재하지 않으면
		if($is_found == FALSE) {
			$stock = new Stock();		// 생성

			$stock->setInventory($this);
			$stock->setPart($part);
			$stock->setQtyNew(0);
		}

		// 재고 수량 변경
		$stock->setQtyNew($stock->qty_new + $qty);

		return $stock;
	}	

}

