<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 */

class Office_repository extends CI_Model {
	private $table_name;

	public function __construct()
	{
		parent::__construct();
		//$this->db = $this->load->database('oracle', TRUE);

		$this->table_name = "gs2_offices";
		$this->table_name = strtoupper($this->table_name);		// oracle 테이블명은 대문자로
	}

	public function getMaxId() {
		$sql = "SELECT MAX(ID) as ID FROM " . $this->table_name;
		$rs = $this->db->query($sql);

		$row = $rs->row();

		return $row->ID;
	}

	public function add($object) {
		if( !is_object($object))
			trigger_error("객체 인스턴스만 가능함!");

		$new_id = $this->newId();
		$this->db->set("ID", $new_id);

		$props = $object->getPropertiesList(); 
		foreach( $props as $prop) {
			// echo $prop;
			if(!empty($object->{$prop})) {
				$this->db->set(strtoupper($prop), $object->$prop);
			}
		}

		$this->db->insert($this->table_name);

		return $new_id;
	}

	public function getList()
	{
		$rs = $this->db->get($this->table_name);

		return $rs->result();
	}

	public function delete($office_id)
	{
		if(empty($office_id)) {
			return FALSE;
		}

		$sql = "delete from " . $this->table_name . " where ID = " . $office_id;
		$rs = $this->db->query($sql);
	}

	////////////////////////////////
	// 공통으로 사용할 수 있을듯 
	////////////////////////////////
	public function newId() {
		$sql = "select max(ID) as new_id from " . $this->table_name;
		$query = $this->db->query($sql);

		return ($query->num_rows) ? $query->row()->NEW_ID + 1 : 1;
	}

	public function truncate() {
		$sql = "truncate table " . $this->table_name;
		$this->db->query($sql);

		echo '\n {$this->table_name} is truncated.';
	}

}

class Office {
	var $id;
	var $code;
	var $name;
	var $has_inventory;
	var $inventory_id;
	var $tel;
	var $address;
	var $memo;
	var $status;

	private $props = array();

	public function __construct($id = '')
	{
		$repl = new ReflectionClass($this);
		$props = $repl->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
		
		foreach($props as $obj) {
			array_push($this->props, $obj->name);
		}
	}

	public function set($val = '') {

	}

	public function _set($val = '') {
		print_r($this->props);
	}

	public function getPropertiesList() {
		return $this->props;
	}
}