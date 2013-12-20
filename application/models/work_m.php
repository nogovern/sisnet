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
		$criteria = array('type' => GS2_OPERATION_TYPE_ENTER);
		$rows = $this->repo->findBy($criteria);

		// Location 해석
		foreach($rows as $row) {
			if($row->getWorkLocation()) {
				$row->location_object = $this->parseLocation($row->getWorkLocation());
			}
		}

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

	// 입고 업무 등록
	public function register($type, $post) {
		
		$part = $this->em->getReference('Entity\Part', $post['part_id']);
		$user = $this->em->getReference('Entity\User', $post['user_id']);
		$office = $this->em->getReference('Entity\Office', $post['office_id']);
		
		// 새로운 업무 객체
		$new = new Entity\Operation($this->em);

		$new->setType($post['work_type']);
		$new->setOperationNumber('SYS' . date("Ymd"));		// 새로운 O/N
		$new->setDateRegister();
		$new->setDateRequest($post['date_request']);
		$new->setStatus('1');
		$new->setMemo($post['memo']);

		$new->setOffice($office);
		$new->setUser($user);

		// 납품처 지정
		$new->setWorkLocation($part->company->id, GS2_LOCATION_TYPE_COMPANY);


		$this->work_model->_add($new);

		// $new_id = $new->id;		// 새로운 operation.id
		
		// 업무 대상 장비 리스트
		$item = new Entity\OperationPart;
		$item->setOperation($new);

		$item->setPart($part);
		$item->setType($post['work_type']);
		$item->setRequestQuantity($post['qty']);		// 요청수량
		$item->setDateRegister();
		$item->setNewFlag(TRUE);						// 신품

		$this->work_model->_add($item);

		// apply to db
		$this->work_model->_commit();

	}

	public function _remap($method) {
		echo 'call';
	}

	//////////
	/// 공통 함수
	//////////

	public function parseLocation($location_str) {
		if(is_null($location_str)) {
			return NULL;
		}

		$arr = explode('@', $location_str);
		$instance = NULL;

		switch ($arr[0]) {
			case GS2_LOCATION_TYPE_COMPANY:
				$instance = $this->em->getReference('Entity\Company', intval($arr[1]));
				break;
			
			case GS2_LOCATION_TYPE_OFFICE:
				$instance = $this->em->getReference('Entity\Office', intval($arr[1]));
				break;
			
			case GS2_LOCATION_TYPE_STORE:
			default:
				$instance = $this->em->getReference('Entity\Store', intval($arr[1]));
				break;
		}

		return $instance;
	}

	/**
	 * Loaction 컬럼에 들어갈 문자열 형식으로 변환
	 * @param  [object] $obj 객체 타입
	 * @return [string] "객체타입@객체ID" 형식의 문자열
	 */
	public function makeLocationString($obj) {
		$prefix = '';

		if($obj instanceof Entity\Office) {
			$prefix = 'O';
		} elseif ($obj instanceof Entity\Company) {
			$prefix = 'C';
		} elseif ($obj instanceof Entity\Store) {
			$prefix = 'S';
		} else {
			return FALSE;
		}

		return $prefix . '@' . $obj->id;
	}
}



