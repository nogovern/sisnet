<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="GS2_STOCKS")
 */
class Stock
{
	const QTY_TYPE_NEW 		= 'new';
	const QTY_TYPE_USED 	= 'used';
	const QTY_TYPE_S200 	= 's200';
	const QTY_TYPE_S500 	= 's500';
	const QTY_TYPE_S600 	= 's600';
	const QTY_TYPE_S900 	= 's900';
	
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_stock_seq")
	 */
	protected $id;

	/**
	 * @ManyToOne(targetEntity="Office", inversedBy="stock_list")
	 * @JoinColumn(name="office_id", referencedColumnName="id")
	 */
	protected $office;

	/**
	 * @ManyToOne(targetEntity="Part", inversedBy="stock_list")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;

	/** @Column(type="integer") */
	protected $qty_minimum = 0;

	/** @Column(type="integer") */
	protected $qty_new = 0;

	/** @Column(type="integer") */
	protected $qty_used = 0;

	/** @Column(type="integer") */
	protected $qty_s100 = 0;				// 발주 된 수량

	/** @Column(type="integer") */
	protected $qty_s200 = 0;				// 설치 중 수량

	/** @Column(type="integer") */
	protected $qty_s500 = 0;

	/** @Column(type="integer") */
	protected $qty_s600 = 0;

	/** @Column(type="integer") */
	protected $qty_s900 = 0;

	// ================== setter ===================
	
	// 매직 메소드 (테스트용)
	public function __get($key) {
		if(strstr($key, "qty_"))
			return $this->$key ? $this->$key : 0;
		return $this->$key;
	}
	
	/**
	 * s100 업무상태의 수량 가져오기
	 * 
	 * @param  boolean $alias if TRUE, - 표시로 반환함
	 * @return integer         [description]
	 */
	public function getQtyS100() {
		return $this->qty_s100;
	}

	public function getQtyS200() {
		return $this->qty_s200;
	}

	public function getQtyS500() {
		return $this->qty_s500;
	}

	public function getQtyS600() {
		return $this->qty_s600;
	}

	public function getQtyS900() {
		return $this->qty_s900;
	}

	// 신품
	public function getQtyNew() {
		return $this->qty_new;
	}

	// 중고 가용
	public function getQtyUsed() {
		return $this->qty_used;
	}

	// 중고 비가용 수량 가져오기
	public function getQtyDisabled() {
		$sum = ($this->qty_s200 + $this->qty_s500 + $this->qty_s600 + $this->qty_s900); 
		return $sum;
	}

	// ================== setter ===================

	public function setOffice($instance = NULL) {
		if($instance){
			$this->office = $instance;
		}

		return $this;
	}

	public function setPart(Part $part) {
		$this->part = $part;

		return $this;
	}

	/**
	 * 신품 기준 수량  (최소 수량)
	 * - 기준 수량은 더하기/빼기 개념이 아님
	 * 
	 * @param [integer] $qty [수량]
	 */
	public function setQtyMinimum($qty) {
		$this->qty_minimum = $qty;
	}

	// 신품 수량  설정
	public function setQtyNew($qty) {
		$this->qty_new = $qty;

	}

	// 중고 수량  설정
	public function setQtyUsed($qty) {
		$this->qty_used = $qty;
	}

	//////////////////
	// 비가용 수량 //
	//////////////////
	public function setQtyS200($qty) {
		$this->qty_s200 = $qty;
	}

	public function setQtyS500($qty) {
		$this->qty_s500 = $qty;
	}

	public function setQtyS600($qty) {
		$this->qty_s600 = $qty;
	}

	public function setQtyS900($qty) {
		$this->qty_s900 = $qty;
	}

	// 발주 수량 (가용/비가용 도 아님)
	public function setQtyS100($qty)
	{
		$this->qty_s100 = $qty;
	}

	// 재고 수량 증가
	public function increase($where, $qty) {
		$valid_where = array('new', 'used', 's200', 's500', 's600', 's900', 's100');

		if(!in_array($where, $valid_where)) {
			log_message('error', __METHOD__ . "$where 는 없는 재고 형식 입니다");
			return FALSE;
		}

		$where = 'qty_' . $where;
		
		$stock =& $this->{$where}; 		// [중요] reference 를 참조해야 함!!
		$stock =  $stock + $qty;
	}

	// 재고 수량 감소
	public function decrease($where, $qty) {
		$valid_where = array('new', 'used', 's200', 's500', 's600', 's900', 's100');

		if(!in_array($where, $valid_where)) {
			log_message('error', __METHOD__ . "$where 는 없는 재고 형식 입니다");
			return FALSE;
		}

		$where = 'qty_' . $where;
		$stock =& $this->{$where}; 		// [중요] reference 를 참조해야 함!!

		if($stock < $qty) {
			log_message('error', __METHOD__ . '- 재고 감소량이 현 재고수량 보다 많습니다');
			return FALSE;
		}

		$stock =  $stock - $qty;
	}
}

