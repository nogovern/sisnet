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
	protected $status = '1';

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
		$filtered_list = array();

		foreach($this->stock_list as $stock) {
			if($stock->office->id === $this->id) {
				$filtered_list[] = $stock;
			}
		}

		return $filtered_list;
	}

	public function isMaster() {
		return ($this->is_master == 'Y') ? TRUE : FALSE;
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

	/**
	 * 사무소로 장비 입고 (flush() 는 하지 않음)
	 * 
	 * @param \Entity\Part $part [description]
	 * @param integer $qty 	수량
	 * @param string $type 	type 문자열
	 *
	 * @return \Entity\Stock
	 */
	public function in($part, $qty, $type = 'new') {

		// 재고 목록 내 $part 장비가 있는지 검색
		$stock = $this->existPart($part);

		// 장비가 존재하지 않으면
		if($stock === NULL) {
			$stock = new Stock();		// 생성

			$stock->setOffice($this);
			$stock->setPart($part);
			$stock->setQtyNew(0);
		}

		// 재고 수량 변경
		$stock->setQtyNew($stock->qty_new + $qty);

		return $stock;
	}

	public function add($part, $qty, $type = 'new') {
		return $this->in($part, $qty, $type);
	}


	/**
	 * 사무소에서 장비 출고
	 * 
	 * @param  \Entity\Part $part
	 * @param  integer $qty  	수량
	 * @param  string $type 	type 문자열
	 * 
	 * @return \Entity\Stock     The Stock class
	 */
	public function out($part, $qty, $type = 'new') {
		$stock = $this->existPart($part);

		if($stock === NULL) {
			return FALSE;
		} 

		// 출고 수량이 재고 수량 보다 클수 없음 
		if( $qty > $stock->qty_new) {
			trigger_error("out quantity is not greater than current quuantity");
			return FALSE;
		}

		$stock->setQtyNew($stock->qty_new - $qty);
		return $stock;
	}

	/**
	 * 사무소창고 내 장비 존재 하는지 검사
	 * 
	 * @param  \Entity\Part $part 	the Part class
	 * @return \Entity\Stock      	없으면 null
	 */
	public function existPart($part) {
		if(!count($this->stock_list))
			return NULL;

		foreach($this->stock_list as $item) {
			if( $part->id == $item->part->id) {
				return $item;			// Stock object
				break;
			}
		}

		return NULL;
	}

}

