<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_OFFICES")
 */
class Office
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_office_seq")
	 */
	protected $id;

	/** @column(type="string", length=20) */
	protected $name;
	
	/** @column(type="string", length=1) */
	protected $type = 'O';							// 창고는 'I'

	/** @Column(type="string", length=1) */
	protected $is_master = 'Y';						// default 값

	/**
	 * @OneToOne(targetEntity="Office")
	 * @JoinColumn(name="office_id", referencedColumnName="id")
	 */
	protected $master;								

	/**
	 * @OneToOne(targetEntity="User")
	 * @JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;

	/** @Column(type="string", length=20) */
	protected $phone;

	/** @Column(type="string", length=100) */
	protected $address;
	
	/** @Column(type="string", length=255) */
	protected $memo;

	/** @Column(type="string", length=1) */
	protected $status;

	/**
	 * 
	 * @OneToMany(targetEntity="Stock", mappedBy="office")
	 */
	protected $stock_list;

	//--------------------------------------------------------

	public function __get($key) {
		return $this->$key;
	}

	public function getStockList() {
		return $this->stock_list;
	}

	/**
	 * 지역의 master 사무소를 선택한다
	 * 자신이 master 이면 office_id 가 NULL 이고, is_master = "Y"
	 * 
	 * @param [type] $instance Office 객체
	 */
	public function setMaster($instance = NULL) {
		if(!$instance){
			return FALSE;
		}

		$this->master = $instance;
		$this->setMasterFlag(FALSE);
	}

	/**
	 * 마스터 사무소 구분 flag 설정
	 * 
	 * @param boolean $boolen TRUE/FALSE
	 */
	public function setMasterFlag($boolen = FALSE) {
		$this->is_master = ($boolen) ? 'Y' : 'N';
	}

	/**
	 * 담당자 지정
	 * 
	 * @param [User] $instance User 인스턴스
	 */
	public function setUser($instance = NULL) {
		if(is_null($instance)){
			return FALSE;
		}
		$this->user = $instance;
	}

	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * 사무소 타입 지정 
	 * @param string $type O: Office/I: Inventory
	 */
	public function setType($type = "O") {
		$this->type = $type;
	}

	public function setMemo($memo) {
		$this->memo = $memo;
	}

	public function setPhone($phone) {
		$this->phone = $phone;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function setStatus($status) {
		$this->status = $status;
	}

}

