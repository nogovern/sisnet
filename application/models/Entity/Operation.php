<?php
/*
작업 (master)
 */
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/* 이 부분을 전역 상수로 갈까? 클래스 const 로 갈까? */
define('GS2_OPERATION_STATUS_A', 100);
define('GS2_OPERATION_STATUS_INSTALL', 200);
define('GS2_OPERATION_STATUS_E', 300);
define('GS2_OPERATION_STATUS_CHAGNE', 400);
define('GS2_OPERATION_STATUS_REPAIR', 500);
define('GS2_OPERATION_STATUS_des', 600);
define('GS2_OPERATION_STATUS_move', 700);
define('GS2_OPERATION_STATUS_trans', 800);
define('GS2_OPERATION_STATUS_s', 900);				/*	상태변경 */

/**
 * @Entity
 * @Table(name="GS2_OPERATIONS")
 */
class Operation
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_operation_seq")
	 */
	protected $id;

	/** @Column(type="string", length=20, name="operation_no") */
	protected $no;									// 작업 번호 

	/** @Column(type="string", length=20) */
	protected $type;
	
	/**
	 * @OneToOne(targetEntity="Office")
	 * @JoinColumn(name="office_id", referencedColumnName="id")
	 */
	protected $office;

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;						// 등록 유저

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $worker;					// 담당 유저

	/** @Column(type="datetime", nullable=true) */
	protected $date_register;	// 요청서 등록일시

	/** @Column(type="datetime") */
	protected $date_request;	// 작업 요청일

	/** @Column(type="datetime") */
	protected $date_modify;		// 작업 상태 변경일시

	/** @Column(type="datetime") */
	protected $date_work;		// 작업일시

	/** @Column(type="datetime") */
	protected $date_finish;		// 작업완료일시

	/** @Column(type="string", length=255) */
	protected $memo;

	/** @Column(type="string", length=1) */
	protected $status;

	/**
	 * @OneToMany(targetEntity="OperationPart", mappedBy="operation")
	 */
	private $items;					// 작업 장비들 목록

	//////////
	// 생성자 //
	//////////
	public function __construct() {
		$this->items = new ArrayCollection();
	}


	// ---------- set -------------
	public function setOffice(Office $ref) {
		$this->office = $ref;
	}

	// 요청자 지정 (필수)
	public function setUser(User $ref) {
		$this->user = $ref;
	}

	// 담당자 지정
	public function setWorker(User $ref) {
		$this->worker = $ref;
	}

	// ---------- get -------------
	public function __get($key) {
		return $this->$key;
	}

	public function getRegisterDate() {
		return ($this->date_register) ? $this->date_register->format('Y-m-d H:i:s') : '';
	}

	
}

