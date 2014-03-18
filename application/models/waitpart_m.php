<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 폐기,수리 대기 장비 모델
*/
class Waitpart_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_deprecated_parts');
		$this->setEntityName('WaitPart');
	}

	public function all() {
		$qb = $this->em->createQueryBuilder();
		$qb->select('p')
			->from($this->entity_name, 'p');

		$query = $qb->getQuery();
		return $query->getResult();
	}

	// 장비 존재하는지 검사 
	public function existPartInList($gubun, $office_id, $part_id) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('p')
			->from($this->entity_name, 'p')
			->where("p.office = $office_id")
			->andWhere("p.gubun = '$gubun'")
			->andWhere("p.part = $part_id")
			->andWhere("p.qty > 0");
		$result = $qb->getQuery()->getResult();

		return count($result) ? $result : false;
	}

	// gs2_part_serial.id 로 찾기 (가장 확실)
	public function searchBySerialId($gubun, $sp_id) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('p')
			->from($this->entity_name, 'p')
			->where("p.gubun = '$gubun'")
			->andWhere("p.serial_part = $sp_id");

		$result = $qb->getQuery()->getResult();

		return count($result) ? $result[0] : false;
		
	}

	// SN으로 찾기
	public function searchBySerailNumber($gubun, $sn) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('p')
			->from($this->entity_name, 'p')
			->where("p.gubun = '$gubun'")
			->andWhere("p.serial_number = $sn");

		$result = $qb->getQuery()->getResult();

		return count($result) ? $result[0] : false;
	}

	// 검색 
	public function search($criteria = array()) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('p')
			->from($this->entity_name, 'p');

		// 조건 (gubun, office, part, serial_number, previous_location)	
		foreach($criteria as $key => $val) {
			// 일반 문자열 match
			if( $key == 'serial_number' || $key == 'gubun') {
				$qb->andWhere("p.$key = '$val'");
			// 직전위치 검색
			} elseif ($key == 'previous_location') {
				if(is_array($val)) {
					$qb->andWhere('p.previous_location IN (:param_1)');
					$qb->setParameter('param_1', $val);
				} else {
					$qb->andWhere("p.$key = '$val'");
				}
			} else if ($key == 'qty') {
				$qb->andWhere("p.$key > $val");		// 폐기 대기 수량
			} else {
				$qb->andWhere("p.$key = $val");
			}
		}
		
		$query = $qb->getQuery();
		$result = $query->getResult();

		return $result;	
	}

	/////////
	// 생성 
	/////////
	public function create($data, $do_flush = false) {
		$wp = new $this->entity_name;

		// $this->load->model('part_m', 'part_model');
		$part = $this->em->getReference("Entity\Part", $data['part_id']);
		$op = $this->em->getReference("Entity\Operation", $data['op_id']);

		$wp->setOperation($op);
		$wp->setPart($part);
		$wp->setGubun($data['gubun']);
		$wp->setQty($data['qty']);
		$wp->setPartType($data['part_type']);
		$wp->setDateRegister();

		// 시리얼장비 는 반드시 폐기,수리 전 등록되어 있어야 함
		if($data['part_type'] == '1') {
			$sp = $this->em->getReference("Entity\SerialPart", $data['serial_id']);
			$wp->setSerialPart($sp);
			$wp->setPreviousLocation($sp->previous_location);

			// 시리얼넘버 는 분실된 장비도 있음
			if($sp->serial_number) {
				$wp->setSerialNumber($sp->serial_number);
			}
		}

		$this->em->persist($wp);

		if($do_flush) {
			$this->em->flush();
		}

		return $wp;
	}	

	/////////
	// 수정
	/////////
	public function update($id, $data, $do_flush = false) {
		$wp = $this->get($id);

		if(isset($data['status'])) {
			$wp->setStatus($data['status']);
		}

		$this->em->persist($wp);
		if($do_flush) {
			$this->em->flush();
		}
		
	}

	/////////
	// 삭제
	/////////
	public function remove($id, $do_flush = false) {
		$wp = $this->get($id);

		$this->em->remove($wp);
		if($do_flush) {
			$this->em->flush();
		}
	}
	
}

