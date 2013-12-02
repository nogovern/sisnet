<?php
/*
작업 (master)
 */
namespace Entity;

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
 * @Table(name="GS2_OPERATIONSS")
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

	/** @column(type="string", length=20) */
	protected $no;									// 작업 번호 

	/** @Column(type="string", length=20) */
	protected $type;
	
	/** @column(type="integer") */
	protected $office_id;

	/** @column(type="integer") */
	protected $user_id;

	/** @column(type="integer") */
	protected $worker_id;

	/** @column(type="datetime", nullable=true) */
	protected $date_register;	// 요청서 등록일시

	/** @column(type="datetime") */
	protected $date_request;	// 작업 요청일

	/** @column(type="datetime") */
	protected $date_modify;		// 작업 상태 변경일시

	/** @column(type="datetime") */
	protected $date_work;		// 작업일시

	/** @column(type="datetime") */
	protected $date_finish;		// 작업완료일시

	/** @column(type="string", length=255) */
	protected $memo;

	/** @Column(type="string", length=1) */
	protected $status;

	public function __get($key) {
		return $this->$key;
	}

	public function getRegisterDate() {
		return ($this->date_register) ? $this->date_register->format('Y-m-d H:i:s') : '';
	}

	
}

