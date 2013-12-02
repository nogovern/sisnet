<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		// Entity 명이 'Entity\User' 도 가능
		$this->setEntityName('User');
		$this->setTableName('gs2_users');
	}
	
	
	/**
	 * 사용자 ID(username) 로 정보 가져오기
	 * 
	 * @param  string $value
	 * @return array [User 객체 배열] 
	 */
	public function getByUsername($value)
	{
		$rows = $this->em->getRepository($this->getEntityName())->findBy(array('username' => $value));

		return $rows;
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



