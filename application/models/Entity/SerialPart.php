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

	/** @column(type="string", length=20) */
	protected $serial_number;

	/**
	 * @OneToOne(targetEntity="Part")
	 * @JoinColumn(name="part_id", referencedColumnName="id")
	 */
	protected $part;
	
	/** @column(type="string", length=30) */
	protected $status_operation;

	/** @column(type="string", length=30) */
	protected $previous_location;

	/** @column(type="string", length=30) */
	protected $current_location;

	/** @Column(type="string", length=1) */
	protected $is_new;

	/** @Column(type="string", length=1) */
	protected $is_valid;

	/** @column(type="datetime") */
	protected $date_enter;

	/** @column(type="datetime") */
	protected $date_install;

	/** @column(type="datetime", name="date_modified") */
	protected $date_modify;

	/** @Column(type="string", length=1) */
	protected $status;

	public function __get($key) {
		return $this->$key;
	}

	public function getRegisterDate() {
		return ($this->date_register) ? $this->date_register->format('Y-m-d H:i:s') : '';
	}

	public function setPart(Entity\Part $part) {
		$this->part = $part;
	}

	
}

