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

	public function remove($id, $do_flush = false) {
		$wp = $this->get($id);

		$this->em->remove($wp);
		if($do_flush) {
			$this->em->flush();
		}
	}
	
}

