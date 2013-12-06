<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Work');
		$this->setTableName('gs2_operations');
	}

	public function getByName($value) {
		$repo = $this->em->getRepository($this->entity_name);
		return $repo->findBy(array('name' => $value));
	}


	public function makeOperationNo() {
		$idx = mt_rand(1, 9999);
		$no = date('Ymd') . sprintf("%05d", $idx);

		return $no;
	}	

}



