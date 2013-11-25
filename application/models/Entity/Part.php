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
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
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
	 * @_OneToMany(targetEntity="InventoryPartAssociation" mappedBy="parts")
	 */
	protected $inventories;

	/* ------------------------------ */
	public function __construct() {
		$this->inventories = new ArrayCollection();
	}

	public function __get($key) {
		return $this->$key;
	}

	/**
	 * setter 정의
	 */
	
	public function setType($val='') {
		$this->type = $val;
	}

	public function setPartNumber($val='') {
		$this->part_no = $val;
	}

	public function setPartCode($val='') {
		$this->part_code = $val;
	}

	public function setName($val='') {
		$this->name = $val;
	}

	public function setRegisterDate() {
		$this->date_register = new \DateTime("now");
	}

	public function setStatus($val='') {
		$this->status = $val;
	}

}

