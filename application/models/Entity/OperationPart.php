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
	 * @SequenceGenerator(sequenceName="gs2_operation_part_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="part_list")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/** @Column(type="string", length=1) */
	protected $type;

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part; 

	/** @Column(type="integer") */
	protected $qty_request; 

	/** @Column(type="integer") */
	protected $qty_complete; 

	/** @Column(type="datetime", nullable=true) */
	protected $date_register;	

	/** @Column(type="datetime", nullable=true) */
	protected $date_modify;

	/** @Column(type="string", length=255) */
	protected $extra;			// 여분 데이타

	/** @Column(type="string", length=1) */
	protected $is_new;

	/** @Column(type="string", length=1) */
	protected $is_complete;

	// ---------- set -------------
	


	// ---------- get -------------
	public function __get($key) {
		return $this->$key;
	}
}