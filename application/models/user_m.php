<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends CI_Model {
	protected $em = NULL;

	public function __construct() {
		parent::__construct();

		// Doctrine load
		$this->load->library('doctrine');
		$this->em = $this->doctrine->em;

		// Entity 명 지정;
		$this->entity_name = 'Entity\Office';
		
		// 태이블명 셋팅 (CI database 로 바로 쿼리 할 경우 사용)
		$this->table_name = 'gs2_users';
		$this->table_name = strtoupper($this->table_name);
	}

	/**
	 * 사용자 추가
	 * @param [Entity\User] $user [description]
	 */
	public function add(Entity\User $user) {
		if(!($user instanceof Entity\User)) {
			trigger_error("인수는 적절한 Object type 이어야 함!");
		}

		// 저장
		$this->em->persist($user);
		$this->em->flush();
	}

	/**
	 * 사용자 추가(저장) -- save, add 같은 기능
	 * @param  Entity\User $user 
	 * @return 없음
	 */
	public function save(Entity\User $user) {
		$this->add($user);
	}

	/**
	 * 전체 사용자 목록을 가져옴
	 * @return Array of Objects [description]
	 */
	public function getList() {
		return $this->em->getRepository('Entity\User')->findAll();
	}

	public function delete($id = NULL) {

	}

	public function find($options) {
		$rows = $this->em->getRepository('Entity\User')->findBy($options);
		
		return $rows;	
	} 

	/**
	 *  ID 번호로 정보 가져오기
	 * 
	 * @param  integer $id User.ID
	 * @return ojbect     User Object or NULL
	 */
	public function get($id) {
		return $this->em->getRepository('Entity\User')->find($id);
	}

	/**
	 * 사용자 ID(username) 로 정보 가져오기
	 * 
	 * @param  string $value
	 * @return array [User 객체 배열] 
	 */
	public function getByUsername($value)
	{
		$rows = $this->em->getRepository('Entity\User')->findBy(array('username' => $value));

		return $rows;
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



