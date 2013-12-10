<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Work_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Operation');
		$this->setTableName('gs2_operations');
		
		$this->repo = $this->em->getRepository($this->getEntityName());
	}

	public function getByName($value) {
		return $repo->findBy(array('name' => $value));
	}

	// 작업번호 생성
	public function makeOperationNo() {
		$prefix = 'SYS';

		$idx = mt_rand(1, 9999);
		$no = $prefix . date('Ymd') . sprintf("%05d", $idx);

		return $no;
	}

	// 입고 목록
	public function getEnterList() {
		$criteria = array('type' => '100');
		$rows = $this->repo->findBy($criteria);

		return $rows;
	}

	// 설치 목록
	public function getInstallList() {
		$criteria = array('type' => '200');
		$rows = $this->repo->findBy($criteria);

		return $rows;
	}

	// 철수 목록
	public function getEvaucationList() {
		$criteria = array('type' => '300');
		$rows = $this->repo->findBy($criteria);

		return $rows;
	}

	public function _remap($method) {
		echo 'call';
	}

}



