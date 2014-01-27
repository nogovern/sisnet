<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_OPERATION_EXTRAS")
 */
class OperationExtra
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_op_extra_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="extras")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/** @Column(type="string", length=1) */
	protected $is_complete = 'N';

	//=====================================================
	
	public function setOperation($op) {
		$this->operation = $op;
	}

	public function setCompleteFlag($flag) {
		$this->is_complete = ($flag == TRUE) ? 'Y' : 'N';
	}

	public function __get($key){
		return $this->{$key};
	}
}

