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
		$repo = $this->em->getRepository($this->getEntityName());
		return $repo->findOneBy(array('username' => $value));
	}

	// user type 별 목록
	public function getListByType($type) {
		$repo = $this->em->getRepository($this->getEntityName());
		$options = array(
			'type'	=> $type
			);
		$rows = $repo->findBy($options);

		return $rows;
	}

	// 사무소별 사용자
	public function getOfficeUsers() {
		$this->load->model('office_m', 'office_model');

		$arr = array();

		$offices = $this->em->getRepository('Entity\Office')->findBy(array(), array('name' => 'ASC'));
		foreach($offices as $o) {
			$users = $this->em->getRepository('Entity\User')->findBy(array('office' => $o, 'status' => '1'), array('name' => 'ASC'));
			
			if(!count($users)) {
				continue;
			}
			
			$arr2 = array();
			foreach($users as $u) {
				$arr2[$u->id] = $u->name;
			}

			$arr[$o->name] = $arr2;
		}

		return $arr;
	}
	
}



