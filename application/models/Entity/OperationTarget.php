<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_OPERATION_TARGETS")
 */
class OperationTarget
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_op_target_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="targets")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/**
	 * @OneToOne(targetEntity="Operation")
	 * @JoinColumn(name="target_operation_id", referencedColumnName="id")
	 */
	protected $target;

	/** @Column(type="string", length=10) */
	protected $gubun = 'change';

	//=====================================================
	
	public function __construct($op, $target) {
		$this->operation = $op;
		$this->target = $target;
	}
	
	// 이 업무 작업 ID
	public function setOperation($op) {
		$this->operation = $op;
	}

	// 대상 작업 ID
	public function setTarget($op) {
		$this->target = $op;
	}

	public function setCompleteFlag($flag) {
		$this->is_complete = ($flag == TRUE) ? 'Y' : 'N';
	}

	// 업무 구분 설정
	public function setGubun($value) {
		$this->gubun = $value;
	}

	public function __get($key){
		return $this->{$key};
	}
}

