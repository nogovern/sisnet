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
 	
 	/**
 	 * 시리얼넘버가 존재하는지 검색
 	 * @param  [string] $sn  시리얼 넘버
 	 * @return [boolean]     시러얼넘버가 있으면 true 반환
 	 */
	public function existSerialNumber($sn) {
		$repo = $this->em->getRepository('Entity\SerialPart');
		$row = $repo->findBy(array('serial_number' => $sn));

		return ($row) ? TRUE : FALSE; 
	}

	/**
	 * 시리얼관리장비 추가
	 * 
	 * @param  [array] $post [description]
	 * @param  [integer] $qty 기본 수량은 1
	 * @return [object]       [description]
	 */
	public function addSerialPart($post, $qty = 1, $type = 'new') {
		if(!count($post)) {
			return FALSE;
		}

		$part = $this->em->getReference('Entity\Part', $post['part_id']);
		if(!$part) {
			trigger_error("존재하지 않는 장비 입니다");
			exit;
		}

		if($this->existSerialNumber($post['serial_number'])) {
			trigger_error('duplicate serial number! S/N must be unique.');
			return FALSE;
		}

		$new = new Entity\SerialPart;
		$new->setPart($part);
		$new->setSerialNumber($post['serial_number']);				// 필수
		$new->setCurrentLocation($post['current_location']);		// 필수
		$new->setPreviousLocation($post['previous_location']);
		$new->setNewFlag($post['is_new']);
		$new->setValidFlag($post['is_valid']);
		$new->setDateModify();
		$new->setMemo($post['memo']);

		// 입고 사무소 찾기
		$office = $this->parseLocation($new->current_location);
		// 재고량 변경
		$stock = $office->in($part, $qty, $type);

		// doctrine persist() 
		$this->_add($new);
		$this->_add($stock);

		// doctrine flush()
		$this->_commit();

		return $new;
	}

}