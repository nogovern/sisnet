<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer_m extends CI_Model {
	protected $em = NULL;

	protected $entity_name;

	public function __construct() {
		parent::__construct();

		// Doctrine load
		$this->load->library('doctrine');
		$this->em = $this->doctrine->em;

		// Entity 명 지정;
		$this->entity_name = 'Entity\Customer';

		
		// 태이블명 셋팅 (CI database 로 바로 쿼리 할 경우 사용)
		$this->table_name = 'gs2_customers';
		$this->table_name = strtoupper($this->table_name);
	}

	public function save($object)
	{
		if(!($object instanceof $this->Entity_name)) {
			trigger_error("인수는 적절한 Object type 이어야 함!");
		}

		// 저장
		$this->em->persist($$object);
		$this->em->flush();
	}

	public function add($object) {
		$this->add($object);
	}

	public function delete($id) {
		;
	}

	public function getList() {
		$repo = $this->em->getRepository($this->entity_name);
		return $repo->findAll();
	}

	public function get($id) {
		$repo = $this->em->getRepository($this->entity_name);
		return $repo->find($id);
	}

	public function find($options) {
		$repo = $this->em->getRepository($this->entity_name);
		return $repo->findBy($options);
	}

	public function getByName($value) {
		$repo = $this->em->getRepository($this->entity_name);
		return $repo->findBy(array('name' => $value));
	}	

	////////////////////////////////
	// 공통으로 사용할 수 있을듯 
	////////////////////////////////
	public function newId() {
		$sql = "select max(id) as new_id from gs2_users";
		$query = $this->db->query($sql);

		return ($query->num_rows) ? $query->row()->NEW_ID + 1 : 1;
	}
}



