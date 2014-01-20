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
	
	/**
	 * 기본 리스트 형식 
	 * 
	 * @param  array   $order  [description]
	 * @param  integer $limit  [description]
	 * @param  integer $offset [description]
	 * @return [type]          [description]
	 */
	public function getList($order_by = array(), $limit = 30, $offset = 0) {
		if(!count($order_by)){
			$order_by = array('id' => 'desc');
		}

		$reop = $this->em->getRepository($this->getEntityName()); 
		return $reop->findBy(array(), $order_by, $limit, $offset);
	}

	// 기본 리스트 형식 2
	public function getList2($criteria=array(), $order_by=array(), $limit = 30, $offset = 0) {
		if(!count($order_by)){
			$order_by = array('id' => 'desc');
		}

		$reop = $this->em->getRepository($this->getEntityName()); 
		return $reop->findBy($criteria, $order_by, $limit, $offset);
	}

	// 검색
	public function find($options) {
		$rows = $this->em->getRepository($this->getEntityName())->findBy($options);
		
		return $rows;	
	}

	// 등록된 row 수 리턴 
	public function getTotalRows($repo=NULL) {
		;
	}

}