<?php
namespace Entity;

/**
 * @Entity
 * @Table(name="GS2_PART_SERIAL")
 */
class SerialPart				// 시리얼 관리 장비
{
	/**
	 * @Id 
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_part_serial_seq")
	 */
	protected $id;

	/** @Column(type="string", length=20, name="serial_number") */
	protected $serial_number;

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;
	
	/** @Column(type="string", length=30) */
	protected $status_operation;

	/** @Column(type="string", length=30) */
	protected $previous_location;

	/** @Column(type="string", length=30) */
	protected $current_location;

	/** @Column(type="string", length=1) */
	protected $is_new = 'Y';

	/** @Column(type="string", length=1) */
	protected $is_valid = 'N';

	/** @Column(type="datetime") */
	protected $date_enter;

	/** @Column(type="datetime") */
	protected $date_install;

	/** @Column(type="datetime", name="date_modified") */
	protected $date_modify;

	/** @Column(type="string", length=1) */
	protected $status;

	/** @Column(type="string", length=255) */
	protected $memo;

	/** 
	 * 대체 장비로 교체되었을 시 교체된 장비의 gs2_part_serial.id 가 들어감
	 * 정상장비는 이 필드가 NULL 이어야 함
	 * 
	 * @OneToOne(targetEntity="SerialPart")
	 * @JoinColumn(name="replace_part_id", referencedColumnName="id")
	 */
	protected $replace_part = NULL;					

	//===================================================
	public function __get($key) {
		return $this->$key;
	}

	// 장비 시리얼 번호
	public function getSerialNumber() {
		return $this->serial_number ? $this->serial_number : '';
	}

	// 현재위치
	public function getCurrentLocation() {
		return $this->current_location;				// 형식 : Entity@ID
	}
	
	// 직전위치 
	public function getPreviousLocation() {
		return $this->previous_location;			// 형식 : Entity@ID
	}

	// 수정일시
	public function getModifyDate($date_only = TRUE) {
		if(empty($this->date_modify))
			return '';
		else
			return ($date_only) ? $this->date_modify->format('Y-m-d') : $this->date_modify->format('Y-m-d H:i:s');
	}

	// 설치일
	public function getInstallDate() {
		return ($this->date_install) ? $this->date_install->format('Y-m-d H:i:s') : '';
	}

	// 입고일
	public function getEnterDate($date_only = TRUE) {
		if(empty($this->date_enter))
			return '';
		else
			return ($date_only) ? $this->date_enter->format('Y-m-d') : $this->date_enter->format('Y-m-d H:i:s');
	}

	public function isNew() {
		return ($this->is_new == 'Y') ? TRUE : FALSE;
	}

	public function isValid() {
		return ($this->is_valid == 'Y') ? TRUE : FALSE;
	}

	//===================================================
	public function setPart($obj) {
		if($obj instanceof Part) {
			$this->part = $obj;
		} else {
			trigger_error(__LINE__ . ' 에러 !!!');
		}
	}

	public function setSerialNumber($str) {
		$this->serial_number = $str;
	}

	public function setStatus($status) {
		$this->status = $status;
	}	

	public function setPreviousLocation($string) {
		$this->previous_location = $string;
	}

	public function setReplacePart($obj) {
		$this->replace_part = $obj;			// Entity\SerailPart 객체여야 함!!
	}

	public function setNewFlag($bool) {
		$this->is_new = ($bool) ? 'Y' : 'N';
	}

	public function setValidFlag($bool) {
		$this->is_valid = ($bool) ? 'Y' : 'N';
	}

	public function setDateEnter($date) {
		$this->date_enter = new \DateTime($date);
	}

	public function setDateInstall($date) {
		$this->date_install = new \DateTime($date);
	}

	public function setDateModify($date) {
		$this->date_modify = new \DateTime($date);
	}

	public function setCurrentLocation($string) {
		$this->current_location = $string;
	}

	public function setMemo($memo) {
		$this->memo = $memo;
	}

}

