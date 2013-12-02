<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	/* Entity Manger */
	protected $em = NULL;

	/* Main Entity Name */
	private $entity_name;

	/* 테이블 명 */
	private $table_name; 

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
	}

	public function getEntityName() {
		return $this->entity_name;
	}

	public function getTableName() {
		return $this->table_name;
	}

	public function getEntityManger() {
		return $this->em;
	}

	//---------------------------------------------------------

	// add or update
	public function save($object)
	{
		if(!($object instanceof $this->entity_name)) {
			trigger_error("인수는 적절한 Object type 이어야 함!");
		}

		// 저장
		$this->em->persist($object);
		$this->em->flush();
	}

	// save 의 alias
	public function add($object) {
		$this->save($object);
	}

	// 삭제
	public function delete($id) {
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

}