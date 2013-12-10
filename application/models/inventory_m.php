<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 창고 모델 
 */
class Inventory_m extends MY_Model {

	public function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_inventories');
		$this->setEntityName('Inventory');

		$this->repo = $this->em->getRepository($this->getEntityName());
	}

	public function addPartToInventory($part, $qty) {
		return FALSE;
	}

	//  창고내 재고 리스트 
	public function getStockList($inventory_id) {
		;
	}

}
