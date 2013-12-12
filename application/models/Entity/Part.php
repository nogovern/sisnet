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
	 * @Id @Column(type="integer", nullable=false) 
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_part_seq")
	 */
	protected $id;

	/** @column(type="string", length=1) */
	protected $type;

	/**
	 * @column(type="string", name="PART_NO", length=20)
	 */
	protected $part_no;

	/** @Column(type="string", length=50) */
	protected $name;

	/** @Column(type="string", length=50) */
	protected $part_code;
	
	/** @Column(type="string", length=50) */
	protected $manufacturer;

	/** 
	 * 납품처
	 * 
	 * @OneToOne(targetEntity="Company") 
	 * @JoinColumn(name="company_id", referencedColumnName="id")
	 */
	protected $company;							

	/** @Column(type="integer") */
	protected $qty_total;

	/** @Column(type="datetime") */
	protected $date_register;

	/** @Column(type="string", length=1) */
	protected $status;

	/** @OneToMany(targetEntity="Stock", mappedBy="part") */
	protected $stock_list;

	/** 
	 * 장비 카테고리
	 * 
	 * @OneToOne(targetEntity="Category") 
	 * @JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;					// 2013.12.11 추가 - 장비 카테고리

	/*
	 상태 정의
	 */
	const STATUS_DISABLE 	= 0;
	const STATUS_ENABLE 	= 1;

	/* ------------------------------ */
	public function __construct() {
		$this->stock_list = new ArrayCollection();
	}

	public function __get($key) {
		if($key == 'company_id') {
			return $this->getCompany();
		} else {
			return $this->$key;
		}
	}

	public function getCompany() {
		return is_null($this->company) ? '' : $this->company->name;
	}

	/**
	 * setter 정의
	 */
	
	/**
	 * 납품처 설정
	 * @param Comapny $obj Company Entity
	 */
	public function setCompany(Comapny $obj) {
		$this->company = $obj;
	}

	/**
	 * 장비 종류 설정
	 * @param Category $obj Category Entity
	 */
	public function setCategory(Category $obj) {
		$this->category = $obj;
	}
	
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

	// 제조사명 (text)
	public function setManufacturer($val='') {
		$this->manufacturer = $val;
	}

	public function setQuantity($val) {
		$this->qty_total = $val;
	}

	// 재고 목록 얻기 - Stock 객체 리스트
	public function getStockList() {
		return $this->stock_list;
	}


	//////////////////
	// 상태 - 불가로 변경 //
	//////////////////
	public function disable() {
		$this->status = 'N';
	}

	//////////
	// 단종  //
	//////////
	public function discontinue() {
		return FALSE;
	}

	public function assignToStockList(Stock $stock) {
		$this->stock_list[] = $stock;
	}

	// 신품 재고 합계 
	public function getNewTotal() {
		$sum = 0;

		foreach($this->stock_list as $stock) {
			$sum += $stock->qty_new;
		}

		return $sum;
	}

	// 중고 재고 합계 
	public function getUsedTotal() {
		$sum = 0;

		foreach($this->stock_list as $stock) {
			$sum += $stock->qty_used;
		}

		return $sum;
	}

}

