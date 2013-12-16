<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Office_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Office');
		$this->setTableName('gs2_offices');
	}

	public function getByName($value) {
		$repo = $this->em->getRepository($this->entity_name);
		return $repo->findBy(array('name' => $value));
	}	

	public function add($post_array) {

		$new = new Entity\Office;
		
		$new->setName($post_array['name']);
		$new->setCode($post_array['code']);
		$new->setTel($post_array['tel']);
		$new->setAddress($post_array['address']);
		$new->setmemo($post_array['memo']);

		// 담당자
		if($post_array['user_id'] != '0') {
			$user = $this->em->getReference('Entity\User', $post_array['user_id']);
		} else {
			$user = NULL;
		}
		$new->setUser($user);

		// 창고
		if($post_array['inventory_id'] != '0') {
			$inventory = $this->em->getReference('Entity\Inventory', $post_array['inventory_id']);
		} else {
			$inventory = NULL;
		}
		$new->setInventory($inventory);

		$this->em->persist($new);
		$this->em->flush();

		return TRUE;
	}
	
	public function save($data) {
		echo 'call!';
		return FALSE;
	}
	
}



