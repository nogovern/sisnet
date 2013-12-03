<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 창고 모델 
 */
class Inventory_m extends MY_Model {
	private $table_name;

	public function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_inventories');
		$this->setEntityName('Inventory');
	}

	public function addPartToInventory($part, $qty) {
		return FALSE;
	}

}
