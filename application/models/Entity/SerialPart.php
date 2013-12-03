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
	 */
	protected $id;

	/** @Column(type="string", length=20) */
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
	protected $is_new;

	/** @Column(type="string", length=1) */
	protected $is_valid;

	/** @Column(type="datetime") */
	protected $date_enter;

	/** @Column(type="datetime") */
	protected $date_install;

	/** @Column(type="datetime", name="date_modified") */
	protected $date_modify;

	/** @Column(type="string", length=1) */
	protected $status;

	public function __get($key) {
		return $this->$key;
	}

	// 수정일시
	public function getModifyDate() {
		return ($this->date_modify) ? $this->date_modify->format('Y-m-d H:i:s') : '';
	}

	// 설치일
	public function getInstallDate() {
		return ($this->date_install) ? $this->date_install->format('Y-m-d H:i:s') : '';
	}

	// 입고일
	public function getEnterDate() {
		return ($this->date_enter) ? $this->date_enter->format('Y-m-d H:i:s') : '';
	}

	public function setPart(Entity\Part $part) {
		$this->part = $part;
	}

	
}

