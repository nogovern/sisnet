<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 리포트 모델
*/
class Report_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_operations');
		$this->setEntityName('Operation');

	}

	// 업무 type 을 키로 가지는 배열을 반환
	function getOperationArray() {
		$keys = range(100, 999, 100);
		$vals = array();
		foreach($keys as $k => $v) {
			$vals[$v] = 0;
		}

		return $vals;
	}


	// 사무소별 작업량
	function getStatsByOffice($off_id, $from = null, $to = null) {
		$works = $this->getOperationArray();

		$qb = $this->em->createQueryBuilder();

		$qb->select("w.type, count(w.id) as cnt")
			->from("Entity\Operation", "w")
			->where("w.office = $off_id")
			->groupBy("w.office, w.type")
			->orderBy("w.type");

		// 기간 검색 쿼리	
		// 기간 검색 쿼리	
		if( $from )  {
			$qb->andWhere("w.date_work >= :from");
			$qb->setParameter('from', $from);
		}

		if( $to )  {
			$qb->andWhere("w.date_work <= :to");
			$qb->setParameter('to', $to);
		}

		$query = $qb->getQuery();
		$rows = $query->getArrayResult();

		// 업무 타입별 작업량으로 변환
		// 
		foreach($rows as $row) {
			$type = (int)$row['type'];

			for($i = 100; $i < 1000; $i += 100) {
				if($type >= $i && $type < ($i + 100)) {
					$works[$i] += $row['cnt'];
				}				
			}
		}

		return $works;
	}

	function getStatsByWorker($worker_id, $from=null, $to=null) {
		$works = $this->getOperationArray();

		$qb = $this->em->createQueryBuilder();

		$qb->select("w.type, count(w.id) as cnt")
			->from("Entity\Operation", "w")
			->where("w.worker = $worker_id")
			->groupBy("w.worker, w.type")
			->orderBy("w.type");

		// 기간 검색 쿼리	
		if( $from )  {
			$qb->andWhere("w.date_work >= :from");
			$qb->setParameter('from', $from);
		}

		if( $to )  {
			$qb->andWhere("w.date_work <= :to");
			$qb->setParameter('to', $to);
		}

		$query = $qb->getQuery();
		$rows = $query->getArrayResult();

		// 업무 타입별 작업량으로 변환
		foreach($rows as $row) {
			$type = (int)$row['type'];

			for($i = 100; $i < 1000; $i += 100) {
				if($type >= $i && $type < ($i + 100)) {
					$works[$i] += $row['cnt'];
				}				
			}
		}

		return $works;
	}
}