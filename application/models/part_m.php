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
			
		// (주의) 
		// DQL 안에서 "" 쓰면 안된다. '' 를 써야함
		// ouble quotation marks ”...” define a terminal string a vertical bar | represents an alternative
		// 
		$qb = $this->em->createQueryBuilder();
		$query = $this->em->createQuery('SELECT sp FROM Entity\SerialPart sp WHERE sp.is_valid = \'Y\' AND sp.replace_part IS NULL ');
		$rows = $query->getResult();
		
		return $rows;

	}

}