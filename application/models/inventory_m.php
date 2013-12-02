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

	////////////////////////////////
	// 공통으로 사용할 수 있을듯 
	////////////////////////////////
	public function newId() {
		$sql = "select max(ID) as new_id from " . $this->table_name;
		$query = $this->db->query($sql);

		return ($query->num_rows) ? $query->row()->NEW_ID + 1 : 1;
	}

}
