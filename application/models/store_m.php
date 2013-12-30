<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
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
}