<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
* 재고 모델
*/
class Stock_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_stocks');
		$this->setEntityName('Stock');
	}

	// 재고 - 장비 리스트를 반환
	public function getStocksWithPart($criteria=array(), $limit=20, $offset=0) {
		
		$qb = $this->em->createQueryBuilder(); 
		$qb->select("s, p")
			->from("Entity\Stock", "s")
			->leftJoin("s.part", "p")		// JOIN
			->orderBy('p.id', 'ASC');

		// 검색조건
		foreach($criteria as $key => $val) {
			if($key == 'office') {
				$qb->andWhere("s.office = $val");
			}

			if($key == 'category') {
				$qb->andWhere("p.category = $val");
			}

			if($key == 'part') {
				$qb->andWhere("p.id = $val");
			}
		}

		$query = $qb->setFirstResult($offset)->setmaxResults($limit)->getQuery();
		return $query->getResult();
	} 

	public function numRows($criteria=array()) {
		$qb = $this->em->createQueryBuilder(); 
		$qb->select("s, p")
			->from("Entity\Stock", "s")
			->leftJoin("s.part", "p")		// JOIN
			->orderBy('p.id', 'ASC');

		foreach($criteria as $key => $val) {
			if($key == 'office') {
				$qb->andWhere("s.office = $val");
			}

			if($key == 'category') {
				$qb->andWhere("p.category = $val");
			}

			if($key == 'part') {
				$qb->andWhere("p.id = $val");
			}
		}

		$result = $qb->getQuery()->getResult();
		return count($result);
	}
}