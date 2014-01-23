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

	// 시리얼 장비 모델 
	// gs2_parts 내 type = 1 인 장비 검색 
	function getSerialPartModelList() {
		$criteria = array( 'type' => '1');
		return $this->find($criteria);
	}

	/**
	 * 재고가 있는 장비 목록
	 * 
	 * @param  string $part_type 인자가 없으면 전체
	 * @return array of objects   장비 object
	 */
	function getListInStock($part_type = '') {
		if(empty($part_type)) {

		}
	}

	/////////////////////////////
	// 시리얼 관리 장비 전용 //
	////////////////////////////
	
	/**
	 * 시리얼 관리 제품 리스트
	 * 
	 * @return array of objects [description]
	 */
	public function getSerialPartList() {
			
		// (주의) 
		// DQL 안에서 "" 쓰면 안된다. '' 를 써야함
		// ouble quotation marks ”...” define a terminal string a vertical bar | represents an alternative
		// 

		$dql = "SELECT sp FROM Entity\SerialPart sp WHERE sp.replace_part IS NULL";
		// $dql = "SELECT sp FROM Entity\SerialPart sp WHERE sp.is_valid = \'Y\' AND sp.replace_part IS NULL";

		$qb = $this->em->createQueryBuilder();
		$query = $this->em->createQuery($dql);
		$rows = $query->getResult();
		
		return $rows;

	}
 	
 	/**
 	 * 시리얼넘버가 존재 여부
 	 * 
 	 * @param  string $sn  시리얼 넘버
 	 * @return boolean     시러얼넘버가 있으면 TRUE
 	 */
	public function existSerialNumber($sn) {
		$row = $this->getPartBySerialNumber($sn);
		return ($row) ? TRUE : FALSE; 
	}

	/**
	 * 시리얼넘버로 장비 찾기
	 * 
	 * @param  string 	$sn [description]
	 * @return object or NULL     [description]
	 */
	public function getPartBySerialNumber($sn) {
		$repo = $this->em->getRepository('Entity\SerialPart');
		$row = $repo->findOneBy(array('serial_number' => $sn));

		return($row) ? $row : NULL;
	}



	/**
	 * 시리얼관리장비 추가
	 * 
	 * @param  array $post 		POST 데이터
	 * @param  integer 	$qty 	기본 수량은 1
	 * @return \Entity\SerialPart   성공시 추가된 object
	 */
	public function addSerialPart($post, $do_flush=FALSE) {
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
		$new->setDateEnter($post['date_enter']);
		if(@!empty($post['date_install'])) {
			$new->setDateInstall($post['date_install']);
		}
		$new->setDateModify();
		$new->setMemo($post['memo']);

		// 입고 사무소 찾기
		$office = gs2_decode_location($new->current_location);
		// 재고량 변경
		$stock = $office->in($part, $post['qty'], 'new');

		// doctrine persist() 
		$this->em->persist($new);
		$this->em->persist($stock);

		if($do_flush) {
			$this->em->flush();
		}

		return $new;
	}

	// 재고 생성
	public function createStock($data, $do_flush=FALSE) {
		$stock = new Entity\Stock;

		$stock->setPart($data['part']);				// Entity
		$stock->setOffice($data['office']);			// Entity
		
		if(isset($data['minimum'])) {
			$stock->setQtyMinimum((int)$data['minimum']);
		}
		
		if(isset($data['new'])) {
			$stock->setQtyNew((int)$data['new']);
		}

		if(isset($data['used'])) {
			$stock->setQtyUsed((int)$data['used']);
		}
		
		if(isset($data['s200'])) {
			$stock->setQtyS200($data['s200']);
		}

		if(isset($data['s400'])) {
			$stock->setQtyS400($data['s400']);
		}

		if(isset($data['s100'])) {
			$stock->setQtyS100($data['s100']);
		}

		$this->em->persist($stock);
		if($do_flush) {
			$this->em->flush();
		}

		return $stock;
	}

	///////////
	// 틀만 만들어 놓은 - 사용 여부는 미정
	////////////
	public function getStock($office_id) {
		$stock_arr = array(
			'part'		=> $part,
			'office'	=> $op->office,
			'new'		=> 0,
			'used' 		=> 0,
			'minimum'	=> 0
		);
	}

	public function getStockIfExist($office_id) {

	}
}

