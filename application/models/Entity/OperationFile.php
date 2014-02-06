<?php
namespace Entity;

/**
 *	업무 첨부 파일
 * 
 * @Entity
 * @Table(name="GS2_OPERATION_FILES")
 */
class OperationFile
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_op_file_seq")
	 */
	protected $id;				

	/**
	 * @ManyToOne(targetEntity="Operation", inversedBy="files")
	 * @JoinColumn(name="operation_id", referencedColumnName="id")
	 */
	protected $operation;
		
	/** @Column(type="string", length=255) */
	protected $org_name;

	/** @Column(type="string", length=255) */
	protected $save_name;

	/** @Column(type="string", length=20) */
	protected $extension;
	
	/** @Column(type="integer") */
	protected $size;
	
	/** @column(type="datetime", nullable=true) */
	protected $date_register;

	//==========================================
	public function __get($key) {
		return $this->$key;
	}

	public function __set($key, $value) {
		if($key != 'id') {
			$this->$key = $value;
		}
	}

	public function getOperation() {
		return $this->operation;
	}


	// 등록일시 얻기
	public function getDateRegister($long = FALSE) {
		$format = ($long) ? 'Y-m-d H:i:s' : 'Y-m-d';
		return ($this->date_register) ? $this->date_register->format($format) : '';
	}

	///////////
	// set  //
	///////////
	public function setOperation($obj) {
		$this->operation = $obj;
	}

	public function setOrginalName($obj) {
		$this->org_name = $obj;
	}

	public function setSaveName($obj) {
		$this->save_name = $obj;
	}

	public function setSize($value) {
		$this->size = $value;
	}

	public function setExtension($value) {
		$this->extension = $value;
	}

	// 등록일시
	public function setDateRegister($date = 'now') {
		if(!empty($date))
			$this->date_register = new \DateTime($date);
	}
}

