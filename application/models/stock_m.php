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

	// 
	/**
	 * 재고 - 장비 리스트를 반환
	 * 
	 * @param  array   $criteria 	검색조건 배열
	 * @param  integer $limit 	한페이에 보일 행수
	 * @param  integer $offset 	가져올 offset
	 * @return [type]
	 */
	public function getStocksWithPart($criteria=array(), $limit=20, $offset=0) {
		
		$qb = $this->em->createQueryBuilder(); 
		$qb->select("s, p")
			->from("Entity\Stock", "s")
			->leftJoin("s.part", "p")		// JOIN
			->orderBy('p.id', 'ASC');

		// 검색조건
		foreach($criteria as $key => $val) {
			if($val > 0 && $key == 'office') {
				$qb->andWhere("s.office = $val");
			}

			if($val > 0 && $key == 'category') {
				$qb->andWhere("p.category = $val");
			}

			if($val > 0 && $key == 'part') {
				$qb->andWhere("p.id = $val");
			}
		}

		$qb->setFirstResult($offset)->setmaxResults($limit);
		$query = $qb->getQuery();

		return $query->getResult();
	} 

	// 테이블 row 수 계산
	public function numRows($criteria=array()) {

		$qb = $this->em->createQueryBuilder(); 
		$qb->select("COUNT(s.id)")
			->from("Entity\Stock", "s")
			->leftJoin("s.part", "p");		

		// 검색조건
		foreach($criteria as $key => $val) {
			if($val > 0 && $key == 'office') {
				$qb->andWhere("s.office = $val");
			}

			if($val > 0 && $key == 'category') {
				$qb->andWhere("p.category = $val");
			}

			if($val > 0 && $key == 'part') {
				$qb->andWhere("p.id = $val");
			}
		}

		$query = $qb->getQuery();
		$count = $query->getSingleScalarResult();

		return $count;
	}

	// 생성
	public function create($data, $do_flush = false) {

	}

	// 수정	
	public function update($id, $data, $do_flush = false) {

	}

	// 삭제
	public function remove($id, $do_flush = false) {

	}
}

