<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Office_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Office');		// 이 시점에 MY_Model::$repo 변수 설정함
		$this->setTableName('gs2_offices');
	}

	public function getByName($value) {
		return $this->repo->findOneBy(array('name' => $value));
	}

	public function getMasterList() {
		$rows = $this->repo->findBy(array('is_master' => 'Y'));

		return $rows;
	}

	// 점포 이름 like 검색
	function findByName($q) {
		$qb = $this->em->createQueryBuilder();
		$qb->select('s')
			->from($this->entity_name, 's')
			->where('s.name LIKE :terms ')
			->setParameter('terms', "%$q%");

		$rows = $qb->getQuery()->getResult();
		return $rows;
	}

	public function add($post_array, $do_flush=FALSE) {

		$new = new Entity\Office;
		
		$new->setName($post_array['name']);
		$new->setType($post_array['type']);
		$new->setPhone($post_array['phone']);
		$new->setAddress($post_array['address']);
		$new->setmemo($post_array['memo']);
		$new->setStatus($post_array['status']);

		// 담당자
		if($post_array['user_id'] != '0') {
			$user = $this->em->getReference('Entity\User', $post_array['user_id']);
		} else {
			$user = NULL;
		}
		$new->setUser($user);

		// 상위 Office 의 ID 지정
		if( $post_array['office_id'] == '0') {
			$new->setMasterFlag(TRUE);
		} else {
			$office = $this->em->getReference('Entity\Office', $post_array['office_id']);
			
			$new->setMaster($office);
			$new->setMasterFlag(FALSE);
		}

		$this->em->persist($new);

		if($do_flush) {
			$this->em->flush();
		}

		return $new;
	}
	
	public function save($data) {
		echo 'call!';
		return FALSE;
	}
	
}

