<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="GS2_STOCKS")
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
	 * @ManyToOne(targetEntity="Office", inversedBy="stock_list")
	 * @JoinColumn(name="office_id", referencedColumnName="id")
	 */
	protected $office;

	/**
	 * @ManyToOne(targetEntity="Part", inversedBy="stock_list")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;

	/** @Column(type="integer") */
	protected $qty_minimum = 0;

	/** @Column(type="integer") */
	protected $qty_new = 0;

	/** @Column(type="integer") */
	protected $qty_used = 0;

	/** @Column(type="integer") */
	protected $qty_s100 = 0;

	/** @Column(type="integer") */
	protected $qty_s400 = 0;

	/**
	 * 매직 메소드 (테스트용)
	 */
	public function __get($key) {
		if(strstr($key, "qty_"))
			return $this->$key ? $this->$key : 0;
		return $this->$key;
	}

	public function setOffice($instance = NULL) {
		if($instance){
			$this->office = $instance;
		}

		return $this;
	}

	public function setPart(Part $part) {
		$this->part = $part;

		return $this;
	}

	/**
	 * 신품 기준 수량  (최소 수량)
	 * - 기준 수량은 더하기/빼기 개념이 아님
	 * 
	 * @param [integer] $qty [수량]
	 */
	public function setQtyMinimum($qty) {
		$this->qty_minimum = $qty;
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

