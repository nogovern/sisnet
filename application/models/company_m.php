<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Company_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		// 테이블명
		$this->setEntityName('Company');
		// Entity명
		$this->setTableName('gs2_companies');
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



