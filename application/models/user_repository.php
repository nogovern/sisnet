<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_repository extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->db = $this->load->database('oracle', TRUE);

		// echo __CLASS__ . " is loaded...";
	}

	public function add($data) {
		if(!is_array($data))
			return FALSE;

		foreach( $data as $k => $v) {
			if("date_register" == $k) {
				$v = "sysdate";
				$this->db->set(strtoupper($k), $v, FALSE);		// 등록일시 
			} else {
				$this->db->set(strtoupper($k), $v);
			}
		}

		// transaction begin
		$this->db->trans_start();

		$this->db->set("ID", $this->newId());
		$this->db->insert("GS2_USERS");

		// transaction end 
		$this->db->trans_complete();

		echo $this->db->last_query();
	}

	public function save() {

	}

	public function delete($id = NULL) {

	} 

	public function get($id) {

	}

	public function lists() {
		$sql = "select * from gs2_users";

		$rs = $this->db->query($sql);
		$rows = $rs->result();

		return $rows;
	}

	public static function find($key = "", $options = "") {
		return FALSE;
	}

	////////////////////////////////
	// 공통으로 사용할 수 있을듯 
	////////////////////////////////
	public function newId() {
		$sql = "select max(id) as new_id from gs2_users";
		$query = $this->db->query($sql);

		return ($query->num_rows) ? $query->row()->NEW_ID + 1 : 1;
	}
}

class User {
	private $id;
	private $gubun;
	private $username;
	private $name;
	private $password;
	private	$phone;
	private	$email;
	private	$fax;
	private $date_register;
	private $status;

	public function __construct() {

	}

	public function set($key='', $value='') {
		$this->$key = $value; 
	}

	public function save() {
		$repo = new User_repository();
	}

}

