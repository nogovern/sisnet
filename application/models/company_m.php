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
		return $this->repo->findBy(array('name' => $value));
	}

	// 업체 타입으로 업체 리스트 얻기
	public function findByType($type = '1') {
		return $this->repo->findBy(array('type' => $type), array('name' => 'ASC'));
	}

	// 외부 거래 업체 리스트 얻기
	public function getClients() {
		$qb = $this->em->createQueryBuilder();
		$qb->select('c')
			->from("Entity\Company", "c")
			->where("c.type >= '3'")
			->andWhere("c.status >= '0'");

		$qb->addOrderBy("c.type", "ASC");
		$qb->addOrderBy("c.name", "ASC");

		$query = $qb->getQuery();
		return $query->getResult();
	}

	////////////////////////////////
	// 공통으로 사용할 수 있을듯 
	////////////////////////////////

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



