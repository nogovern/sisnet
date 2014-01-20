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
	function getByName($name) {
		return $this->repo->findOneBy(array('name' => $value));
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
			->from('\Entity\Store', 's')
			->where('s.name LIKE :terms ')
			->setParameter('terms', "%$q%");

		$rows = $qb->getQuery()->getResult();
		return $rows;
	}

	// 생성
	function create($post) {
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

	// 수정
	public function update($id, $post) {

	}
}