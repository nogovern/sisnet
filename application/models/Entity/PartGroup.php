<?php
namespace Entity;

/**
 *	장비 그륩
 * 
 * @Entity
 * @Table(name="GS2_PART_GROUPS")
 */
class PartGroup
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_part_group_seq")
	 */
	protected $id;				// 트리거로 자동 생성

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;
	
	/** @Column(type="integer") */
	protected $qty = 1;
	
	/** @column(type="string", length=30) */
	protected $name;

	/** @Column(type="string", length=255) */
	protected $description;
	
	//==========================================
	public function __get($key) {
		return $this->$key;
	}

	public function getQty() {
		return $this->qty;
	}

	public function getPart() {
		return $this->part;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	///////////
	// set  //
	///////////
	public function setPart($obj) {
		$this->part = $obj;
	}

	public function setDescription($value) {
		$this->description = $value;
	}

	public function setQty($value=0)
	{
		$this->qty = $value;
	}

	public function setName($value)
	{
		$this->name = trim($value);
	}

}

