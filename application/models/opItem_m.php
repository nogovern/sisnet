<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 작업 등록 장비 모델
*/
class OpItem_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_opeartion_parts');
		$this->setEntityName('OperationPart');
	}

	
	function all($operation_id = null) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('i')
			->from("Entity\OperationPart", "i")
			;

		// 작업 ID 가 있을때 처리 (거의 대부분)
		if(! is_null($operation_id))
			$qb->where("i.operation = $operation_id");

		$rows = $qb->getQuery()->getResult();

		return $rows;
	}

	// create
	function insert() {

	}

	// delete
	function remove() {

	}

	// update
	function update() {

	}
	
	
	
}

