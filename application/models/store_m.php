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

	// 점포명으로 검색(정확)
	function getByName($value) {
		return $this->repo->findOneBy(array('name' => $value));
	}

	// 점포 코드 로 검색(정확)
	function getByCode($value) {
		return $this->repo->findOneBy(array('code' => $value));
	}

	/**
	 * 점포명에서 일부 단어로 검색 하여 결과 목록 반환
	 * 
	 * @param  string $q 	검색어
	 * @return array   		array of Entity\Store 
	 */
	function findByName($q) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('s')
			->from($this->entity_name, 's')
			->where('s.name LIKE :terms ')
			->setParameter('terms', "%$q%");

		$rows = $qb->getQuery()->getResult();
		return $rows;
	}

	// 생성
	function create($data) {
		$store = new Entity\Store();

		$store->code 		= $this->input->post('code');
		$store->code2 		= $this->input->post('code2');
		$store->name 		= $this->input->post('name');
		$store->owner_name 	= $this->input->post('owner_name');
		$store->owner_tel 	= $this->input->post('owner_tel');
		$store->tel 		= $this->input->post('tel');
		$store->address 	= $this->input->post('address');
		$store->rfc_name 	= $this->input->post('rfc_name');
		$store->rft_tel 	= $this->input->post('rft_tel');
		$store->ofc_name 	= $this->input->post('ofc_name');
		$store->ofc_tel 	= $this->input->post('ofc_tel');
		$store->join_type 	= $this->input->post('join_type');
		$store->has_postbox = $this->input->post('has_postbox');
		$store->status 		= $this->input->post('status');
		$store->setDateRegister();

		$this->em->persist($store);
		$this->em->flush();

		return $store;
	}

	// 점포 정보 수정
	public function update($id, $data, $do_flush = FALSE) {
		$store = $this->get($id);

		// 매직 메소드 사용하여 단순화 함
		foreach($data as $key => $val) {
			if($key == 'id') {
				continue;
			}

			$this->$key = $val;
		}

		$this->em->persist($store);

		if($do_flush) {
			$this->em->flush();
		}

		return $store;
	}

	// 휴점 장비 리스트 (기본적으로 operation 으로 검색)
	public function getRestPartList($op) {
		$repo = $this->em->getRpository('Entity\RestPart');
		$items = $repo->findBy(array('operation' => $op, 'is_install' => 'N'));

		return $items;
	}


	// 휴점 장비 리스트 - 점포 id 로 검색
	public function getRestPartListByStore($store_id) {
		$repo = $this->em->getRpository('Entity\RestPart');

		$store = $this->em->getReference('Entity\Store', $store_id);
		$items = $repo->findBy(array('store' => $store));

	}

	// 휴점 장비 등록(추가)
	// 필수 데이터 - id, store_id, part_id, qty
	public function addRestPart($data, $do_flush = FALSE) {

		$item = new Entity\RestPart;

		$op = $this->em->getReference('Entity\Operation', $data['id']);
		$item->operation = $op;

		$part = $this->em->getReference('Entity\Part', $data['part_id']);
		$item->part = $part;

		$store = $this->em->getReference('Entity\Store', $data['store_id']);
		$item->store = $store;

		$item->qty = $data['qty'];		// 수량
		$item->is_install = 'N';		// 휴점 장비 설치 flag

		$item->setDateRegister();
		
		$this->em->persist($item);

		if($do_flush) {
			$this->em->flush();
		}

		return $item;
	}

	// 휴점 장비 삭제
	public function removeRestPart($item_id) {
		;
	}

	// 철수 가능한 점포 리스트 (status = 1)
	// 정상 운영중인 곳
	public function getListInNormal() {
		return FALSE;
	}

	// 휴점 설치 가능한 점포 리스트
	public function getListInRest() {
		return FALSE;
	}

	// 점포 상태 변경
	public function setStatus($store_id, $status, $do_flush = FALSE) {
		$store = $this->get($store_id);

		$store->setStatus($status);

		$this->em->persist($store);

		if($do_flush) {
			$this->em->flush();
		}
	}
}

