<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Office_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Office');		// 이 시점에 MY_Model::$repo 변수 설정함
		$this->setTableName('gs2_offices');
	}

	public function getByName($value) {
		return $this->repo->findBy(array('name' => $value));
	}

	public function getMasterList() {
		$rows = $this->repo->findBy(array('is_master' => 'Y'));

		return $rows;
	}

	public function add($post_array) {

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
			echo '=========';
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
		$this->em->flush();

		return TRUE;
	}
	
	public function save($data) {
		echo 'call!';
		return FALSE;
	}
	
}

