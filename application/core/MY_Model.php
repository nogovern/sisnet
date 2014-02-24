<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	/* Entity Manger */
	protected $em = NULL;

	/* Main Entity Name */
	protected $entity_name;

	/* 테이블 명 */
	protected $table_name;

	/* 테이블 명 */
	protected $repo = NULL;

	////////////
	// 생성자 //
	////////////
	public function __construct() {
		parent::__construct();

		// Doctrine load
		$this->load->library('doctrine');
		$this->em = $this->doctrine->em;
	}

	// 태이블명 셋팅 (CI database 로 바로 쿼리 할 경우 사용)
	protected function setTableName($name) {
		$this->table_name = strtoupper($name);
	}

	/**
	 * Entity 명을 셋팅
	 * @param [string] $name 
	 */
	protected function setEntityName($name) {
		if(stripos($name, 'Entity\\')) {
			$this->entity_name = $name;
		} else {
			$this->entity_name = 'Entity\\' . ucfirst($name);
		}

		// 편의 위해 repository 셋팅
		$this->repo = $this->em->getRepository($this->entity_name);
	}

	public function getEntityName() {
		return $this->entity_name;
	}

	public function getTableName() {
		return $this->table_name;
	}

	public function getEntityManager() {
		return $this->em;
	}

	/**
	 * doctrine 의 getReference 를 사용하기 위한 wrapper
	 * 
	 * @param  integer $id         [description]
	 * @param  string $entity_name entity type
	 * @return object              entity object
	 */
	public function getReference($id, $entity_name = NULL) {
		if(is_null($entity_name)) {
			$entity_name = $this->entity_name;
		}

		return $this->em->getReference($entity_name, $id);
	}

	//---------------------------------------------------------

	/**
	 * 변경할 객체를 추가한다
	 * @param [object] $object [description]
	 */
	public function _add($object) {
		$this->em->persist($object);
	}

	/**
	 * 최종 변경 저장
	 * @return [none]
	 */
	public function _commit()
	{
		$this->em->flush();
	}

	// 삭제
	public function _delete($obj) {
		;
	}

	/**
	 *  ID 번호로 정보 가져오기
	 * 
	 * @param  integer $id User.ID
	 * @return ojbect     User Object or NULL
	 */
	public function get($id) {
		return $this->em->getRepository($this->getEntityName())->find($id);
	}
	
	/**
	 * 검색 없는 리스트 형식 
	 * 
	 * @param  array   $order  [description]
	 * @param  integer $limit  [description]
	 * @param  integer $offset [description]
	 * @return [type]          [description]
	 */
	public function getList($order_by = array(), $limit = 20, $offset = 0) {
		return $this->getListBy(array(), $order_by, $limit, $offset);
	}

	// 필터 적용된 리스트 반환
	public function getListBy($criteria=array(), $order_by=array(), $limit = 20, $offset = 0) {
		if(!count($order_by)){
			$order_by = array('id' => 'desc');
		}

		$repo = $this->em->getRepository($this->getEntityName()); 
		return $repo->findBy($criteria, $order_by, $limit, $offset);
	}

	// 결과행 수를 반환 (검색조건 가능)
	public function getRowCount($criteria = array(), $table_name=NULL) {
		if(NULL === $table_name) {
			$table_name = $this->entity_name;
		}

		if(count($criteria)) {
			$rows = $this->em->getRepository($table_name)->findBy($criteria);
			return sizeof($rows);
		} 
		// 검색 조건이 없을경우 성능 향상 위해서
		else {
			$dql = "select count(t.id) FROM {$table_name} t";
			$query = $this->em->createQuery($dql);
			$count = $query->getSingleScalarResult();

			return $count;
		}
	}

	// 결과행 수를 dql 형식으로 계산
	public function getRowCount2($criteria) {
		$qb = $this->em->createQueryBuilder();

	}

	// 모든 리스트 불러오기
	public function getListAll($entity_name=NULL) {
		if(NULL === $entity_name) {
			$entity_name = $this->entity_name;
		}

		$repo = $this->em->getRepository($entity_name); 
		return $repo->findAll();
	}

	// 검색
	public function find($options) {
		$rows = $this->em->getRepository($this->getEntityName())->findBy($options);
		
		return $rows;	
	}

	// 직전위치 배열을 넘겨줌
	public function getPreviousLocationArray($term) {
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

		return $results;
	}

}


