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

	public function create($post, $do_flush=FALSE) {
		$company = new Entity\Company;

		$company->setName($post['name']);
		$company->setCode($post['code']);
		$company->setType($post['type']);
		$company->setTel($post['tel']);
		$company->setAddress($post['address']);
		$company->setMemo($post['memo']);
		$company->setDateRegister();
		$company->setStatus('Y');			// 기본으로 

		if(isset($post['user_id']) && !empty($post['user_id'])) {
			$user = $this->em->getReference('Entity\User', $post['user_id']);
			$company->setUser($user);
		}

		$this->em->persist($company);
		if($do_flush){
			$this->em->flush();
		}

		return $company;
	}
}



