<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_OPERATION_PARTS")
 */
class OperationPart {
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_operationpart_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="part_list")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/** @Column(type="string", length=1) */
	protected $type;

	/** @Column(type="integer") */
	protected $part_id; 

	/** @Column(type="string", length=20) */
	protected $part_category; 

	/** @Column(type="string", length=20, name="part_name") */
	protected $name;

	/** @Column(type="string", length=20, name="part_qty") */
	protected $qty; 

	/** @Column(type="datetime", nullable=true) */
	protected $date_register;	

	/** @Column(type="datetime", nullable=true) */
	protected $date_modify;

	/** @Column(type="string", length=255) */
	protected $extra;			// 여분 데이타

	// ---------- set -------------
	


	// ---------- get -------------
	public function __get($key) {
		return $this->$key;
	}
}