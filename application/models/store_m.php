<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Storm 모델
*/
class Store_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_stores');
		$this->setEntityName('Store');
	}

	function findByName($q) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('s')
			->from('\Entity\Store', 's')
			->where('s.name LIKE :terms ')
			->setParameter('terms', "%$q%");

		$rows = $qb->getQuery()->getResult();
		return $rows;
	}

	// 생성
	function create($post) {

	}

	// 수정
	public function update($id, $post) {

	}
}