<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 장비 모델
*/
class Part_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_parts');
		$this->setEntityName('Part');
	}


	/////////////////////////////
	// 시리얼 관리 장비 전용 //
	////////////////////////////
	
	// 시리얼 관리 제품 목록
	public function getSerialPartList() {
		$repo = $this->em->getRepository('Entity\SerialPart');
		// $rows = $repo->findAll();
		$rows = $repo->findBy(array(), array('id' => 'asc'));		// 정렬

		return $rows;
	}

}