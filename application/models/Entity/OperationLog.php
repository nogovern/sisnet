<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_OPERATION_LOGS")
 */
class OperationLog
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_op_log_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="logs")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;
	
	/** @Column(type="string", length=1) */
	protected $type = '1';

	/** @Column(type="string", length=30) */
	protected $event = '';

	/** @Column(type="string", length=4000) */
	protected $content;

	/** @Column(type="datetime") */
	protected $date_register;

	/** @Column(type="datetime") */
	protected $date_view;

	//=====================================================
	
	public function setOperation($op) {
		$this->operation = $op;
	}

	public function setUser($user) {
		$this->user = $user;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function setContent($text) {
		$this->content = trim($text);
	}

	// 등록일시
	public function setDateRegister($when = 'now') {
		$this->date_register = new \DateTime($when);
	}

	public function setDateView() {
		$this->date_view = new \DateTime("now");
	}

	// 시스템 로그 시 이벤트 스트링
	public function setEvent($value) {
		$this->event = trim($value);
	}

	//=====================================================
	// magic method
	public function __get($key) {
		return $this->$key;
	}

	public function getEvent() {
		return $this->event;
	}
	
	// 등록일시 얻기
	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}
}

