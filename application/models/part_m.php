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

	// 특정 카테고리의 장비 모델 목록
	public function getModels($category_id ) {
		$criteria['category'] = $category_id;
		return $this->getModelsBy($criteria);		
	}

	// 특정 카테고리 + 조건 장비 모델 검색
	public function getModelsBy($criteria = array(), $limit=0, $offset=0) {
		
		$qb = $this->em->createQueryBuilder();
		$qb->select('p')
			->from('\Entity\Part', 'p')
			->orderBy('p.category', 'ASC');

		foreach($criteria as $key => $val) {
			if($val > 0) {

				if( $key == 'part') {
					$key = "id";
				}

				$qb->andWhere("p.$key = $val");
			}
		}
		
		$qb->setFirstResult($offset)->setmaxResults($limit);
		$result = $qb->getQuery()->getResult();

		return $result;
	}

	// 결과 수 반환
	public function numRows($criteria = array()) {

		$qb = $this->em->createQueryBuilder(); 
		$qb->select('count(p.id)')
			->from('\Entity\Part', 'p')
			->orderBy('p.category', 'ASC');

		foreach($criteria as $key => $val) {
			if($val > 0) {

				if( $key == 'part') {
					$key = "id";
				}

				$qb->andWhere("p.$key = $val");
			}
		}

		$query = $qb->getQuery();
		$count = $query->getSingleScalarResult();

		return $count;
	}

	// 소모품 제외한 모델 목록
	public function getModelsExceptAccessory($category_id) {
		$qb = $this->em->createQueryBuilder();
		
		$qb->select('p')
			->from('\Entity\Part', 'p')
			->where("p.category = :cat")
			->andWhere("p.type != '3' ")				// 소모품 제외
			->orderBy('p.name', 'ASC')
			->setParameter('cat', $category_id);

		$result = $qb->getQuery()->getResult();
		return $result;
	} 

	// 시리얼 장비 모델 
	// gs2_parts 내 type = 1 인 장비 검색 
	public function getSerialPartModelList() {
		$criteria = array( 'type' => '1');
		return $this->find($criteria);
	}


	/////////////////////////////
	// 시리얼 관리 장비 전용 //
	////////////////////////////

	/**
	 * gs2_part_serial.id 로 시리얼장비 Entity 1개 반환
	 * 
	 * @param  integer $sp_id [description]
	 * @return object        [description]
	 */
	public function getSerialPart($sp_id){
		$sp = $this->em->getRepository('Entity\SerialPart')->find($sp_id);
		return $sp;
	}	
	
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


	// 2014.02.02 by JKH
	// 시리얼 장비 리스트 - 페이징 및 필터 적용 위해서 추가
	public function getSerialPartsBy($criteria=array(), $order_by=array(), $limit=15, $offset=0) {
		if(!count($order_by)) {
			$order_by = array( 'id' => 'desc');
		}

		$repo = $this->em->getRepository('Entity\SerialPart');
		$rows = $repo->findBy($criteria, $order_by, $limit, $offset);

		return $rows;
	}
 	
 	/**
 	 * 시리얼넘버가 존재 여부
 	 * 
 	 * @param string $sn  시리얼 넘버
 	 * @param integer $office_id 재고 사무소, NULL 이면 전체 검색
 	 * @param boolean $is_all 전체 검색 여부, default FALSE 이면 가용 장비만 
 	 * @return boolean     시리얼넘버가 있으면 TRUE
 	 */
	public function existSerialNumber($sn, $office_id = NULL, $is_all = FALSE) {
		$row = $this->getPartBySerialNumber($sn, $office_id, $is_all);
		return ($row) ? TRUE : FALSE; 
	}

	/**
	 * 시리얼넘버로 장비 찾기
	 * 
	 * @param string  $sn
 	 * @param integer $office_id 재고 사무소, NULL 이면 전체 검색
 	 * @param boolean $is_all 전체 검색 여부, default FALSE 이면 가용 장비만 
	 * @return object or NULL     [description]
	 */
	public function getPartBySerialNumber($sn, $office_id = NULL, $is_all = FALSE) {
		$repo = $this->em->getRepository('Entity\SerialPart');

		// 검색 조건
		$criteria['serial_number'] = trim($sn);

		// 사무소 에 있는 장비만
		if($office_id && is_numeric($office_id)) {
			// $office = $this->em->getReference('Entity\Office', $office_id);
			$criteria['current_location'] = 'O@' . $office_id;
		}

		// 가용장비에서만 검색 할 경우
		if(!$is_all) {
			$criteria['is_valid'] = 'Y';
		}

		$row = $repo->findOneBy($criteria);

		return($row) ? $row : NULL;
	}


	/**
	 * 설치 업무용 시리얼장비 얻기 (미완성)
	 * 	시리얼넘버로 gs2_part_serial 테이블에서 비가용, 해당 사무소, status = 1,2 인 경우 반환
	 * 
	 * @param  [type] $sn        [description]
	 * @param  [type] $office_id [description]
	 * @return [type]            [description]
	 */
	public function getSerialPartForClose($sn, $office_id=NULL) {
		$qb = $this->em->createBuilder();
		$qb->select("sp")->from("Entity\SeriapPart sp");
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

		// flag 변수
		$is_new = ($post['is_new'] == 'Y') ? TRUE : FALSE;
		$is_valid = ($post['is_valid'] == 'Y') ? TRUE : FALSE;

		$new = new Entity\SerialPart;
		$new->setPart($part);
		$new->setSerialNumber($post['serial_number']);				// 필수
		$new->setCurrentLocation($post['current_location']);		// 필수
		$new->setPreviousLocation($post['previous_location']);
		$new->setNewFlag($is_new);
		$new->setValidFlag($is_valid);
		$new->setDateModify();


		if(isset($post['memo'])) {
			$new->setMemo($post['memo']);
		}
		
		if(isset($post['date_enter'])) {
			$new->setDateEnter($post['date_enter']);
		}
		
		if(isset($post['date_install'])) {
			$new->setDateInstall($post['date_install']);
		}

		if(isset($post['status'])) {
			$new->setStatus($post['status']);
		}

		// doctrine persist() 
		$this->em->persist($new);

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

	/**
	 * 시리얼장비 정보 변경
	 * 	- 이것을 사용해야 위치 변경 시 log를 통합하여 관리 가능
	 * 	
	 * @param  [type]  $sp       [description]
	 * @param  array   $data     [description]
	 * @param  boolean $do_flush [description]
	 * @return [type]            [description]
	 */
	public function updateSerialPart($sp, $data = array(), $do_flush = false) {

		// 위치 정보는 location 형식 string 으로 받는다
		if(isset($data['current_location'])) {
			$sp->setCurrentLocation($data['current_location']);
		}

		if(isset($data['previous_location'])) {
			$sp->setPreviousLocation($data['previous_location']);
		}

		if(isset($data['status'])) {
			$sp->setStatus($data['status']);
		}

		if(isset($data['is_valid'])) {
			$sp->setValidFlag($data['is_valid']);
		}

		if(isset($data['is_new'])) {
			$sp->setNewFlag($data['is_new']);
		}

		if(isset($data['date_enter'])) {
			$sp->setDateEnter($data['date_enter']);
		}

		$this->em->persist($sp);
		
		if($do_flush) {
			$this->em->flush();
		}

		return $sp;
	}

	// 재고 테이블을 셋업한다.
	// 사무소-장비 의 데이터가 없으면 기본 생성
	public function setupStock(Entity\Part $part) {
		// $part 는 필수이므로...
		if(is_null($part)) {
			return FALSE;
		}

		// 모든 사무소 리스트
		$offices = $this->em->getRepository('Entity\Office')->findAll();

		$count = 0;
		foreach($offices as $office) {
			$stock = $part->getStock($office->id);
			// 없으면 생성
			if(!$stock) {
				$stock_arr = array(
					'part'		=> $part,
					'office'	=> $office,
				);
				$stock = $this->createStock($stock_arr);
				$this->em->persist($stock);

				$count++;
			}
		}

		$this->em->flush();

		$result = sprintf("%d 의 재고 데이터가 생성되었습니다", $count);

		return $result;
	}

	/**
	 * 시리얼 장비 직전위치로 검색
	 *
	 * @param integer	$office_id	NULL이면 전체 사무소에서 검색
	 * @param boolean	$is_all 	default FALSE, FALSE 이면 가용 장비에서만 검색	
	 */
	public function searchByPreviousLocation($term, $office_id = NULL, $is_all = FALSE) {
		// 검색어 decoding
		$term = urldecode($term);

		// 최종 검색 결과를 담는 배열
		$results = array();

		// 사무소
		$this->load->model('office_m', 'office_model');
		$offices = $this->office_model->findByName($term);
		$arr1 = array();
		foreach($offices as $o) {
			$arr1[] = gs2_encode_location($o);
		}

		// 점포 
		$this->load->model('store_m', 'store_model');
		$stores = $this->store_model->findByName($term);
		$arr2 = array();
		foreach($stores as $s) {
			$arr2[] = gs2_encode_location($s);
		}

		// O@1, S@1 형태의 배열
		$results = array_merge($arr1, $arr2);

		// 시리얼 장비 직전위치 검색
		$qb = $this->em->createQueryBuilder();
		$qb->select('s')
			->from('Entity\SerialPart', 's')
			->where('s.previous_location IN (:param_1)')
			->setParameter('param_1', $results);

		if(!$is_all) {
			$qb->andWhere("s.is_valid = 'Y'");
		}

		if($office_id) {
			$qb->andWhere("s.current_location = :param_2 ");
			$qb->setParameter('param_2', 'O@' . $office_id);
		}

		$rows = $qb->getQuery()->getResult();
		return $rows;
	}

	// 생성
	public function create($data, $do_flush = false) {

	}

	// 수정	
	public function update($id, $data, $do_flush = false) {

	}

	// 삭제
	public function remove($id, $do_flush = false) {

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

