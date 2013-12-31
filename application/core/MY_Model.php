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
	
	// 목록
	public function getList() {
		return $this->em->getRepository($this->getEntityName())->findAll();
	}

	// 검색
	public function find($options) {
		$rows = $this->em->getRepository($this->getEntityName())->findBy($options);
		
		return $rows;	
	} 

	/**
	 * location 문자열을 해석하여 적절한 객체로 반환
	 * @param  [string] $location_str x@ID 형태의 문자열
	 * @return [object]               Company|Office|Store object entity
	 */
	public function parseLocation($location_str) {
		if(is_null($location_str)) {
			return NULL;
		}

		$arr = explode('@', $location_str);
		$instance = NULL;

		switch ($arr[0]) {
			case GS2_LOCATION_TYPE_COMPANY:
				$instance = $this->em->getReference('Entity\Company', intval($arr[1]));
				break;
			
			case GS2_LOCATION_TYPE_OFFICE:
				$instance = $this->em->getReference('Entity\Office', intval($arr[1]));
				break;
			
			case GS2_LOCATION_TYPE_STORE:
			default:
				$instance = $this->em->getReference('Entity\Store', intval($arr[1]));
				break;
		}

		return $instance;
	}

	/**
	 * Loaction 컬럼에 들어갈 문자열 형식으로 변환
	 * @param  [object] $obj 객체 타입
	 * @return [string] "객체타입@객체ID" 형식의 문자열
	 */
	public function makeLocationString($obj) {
		$prefix = '';

		if($obj instanceof Entity\Office) {
			$prefix = 'O';
		} elseif ($obj instanceof Entity\Company) {
			$prefix = 'C';
		} elseif ($obj instanceof Entity\Store) {
			$prefix = 'S';
		} else {
			return FALSE;
		}

		return $prefix . '@' . $obj->id;
	}

	public function makerLocation($obj) {
		return $this->makeLocationString($obj);
	}

}