<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="GS2_PARTS")
 */
class Part
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false) @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_part_seq")
	 */
	protected $id;

	/**
	 * @column(type="string", length=1)
	 */
	protected $type;

	/**
	 * @column(type="string", name="PART_NO", length=20)
	 */
	protected $part_no;

	/**
	 * @column(type="string", length=50)
	 */
	protected $name;

	/**
	 * @Column(type="string", length=50)
	 */
	protected $part_code;
	
	/**
	 * @Column(type="string", length=50)
	 */
	protected $manufacturer;

	/**
	 * @Column(type="integer")
	 */
	protected $company_id;

	/**
	 * @Column(type="integer")
	 */
	protected $qty_total;

	/**
	 * @Column(type="datetime")
	 */
	protected $date_register;

	/**
	 * @Column(type="string", length=1)
	 */
	protected $status;

	/**
	 * 
	 * @OneToMany(targetEntity="Stock", mappedBy="part")
	 */
	protected $stock_list;

	/* ------------------------------ */
	public function __construct() {
		$this->stock_list = new ArrayCollection();
	}

	public function __get($key) {
		return $this->$key;
	}

	/**
	 * setter 정의
	 */
	
	public function setType($val='') {
		$this->type = $val;
		return $this;
	}

	public function setPartNo($val='') {
		$this->part_no = $val;
		return $this;
	}

	public function setPartCode($val='') {
		$this->part_code = $val;
	}

	public function setName($val='') {
		$this->name = $val;
		return $this;
	}

	public function setRegisterDate() {
		$this->date_register = new \DateTime("now");
		return $this;
	}

	public function setStatus($val='') {
		$this->status = $val;
	}

	public function setManufacturer($val='') {
		$this->manufacturer = $val;
	}

	public function setQuantity($val) {
		$this->qty_total = $val;
	}

	public function getStockList() {
		return $this->stock_list;
	}

	public function assignToStockList(Stock $stock) {
		$this->stock_list[] = $stock;
	}

}

