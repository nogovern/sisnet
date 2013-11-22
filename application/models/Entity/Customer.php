<?php
namespace Entity;


/**
 * @Entity
 * @Table(name="GS2_CUSTOMERS")
 */
class Customer
{
	/**
	 * @Id
	 * @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="customer_id_seq")
	 */
	protected $id;

	/**
	 * @column(type="string", length=20)
	 */
	protected $code;

	/**
	 * @Column(type="string", length=1)
	 */
	protected $type;
	
	/**
	 * @Column(type="string")
	 */
	protected $name;

	protected $tel;
	protected $address;
	protected $user_id;
	protected $memo;

	/**
	 * @Column(type="datetime", nullable=true)
	 */
	protected $date_register;
	protected $status;

	public function __get($key) {
		return $this->$key;
	}

	public function __set($key, $value) {
		if($key == 'id') {
			trigger_error("ID(PK)는 설정할수 없습니다.");
		}

		$method = 'set' . ucfirst($key);	// setName 형식의 메서드 명
		if(method_exists($this, $method)) {
			$this->$method($value);
		} else {
			$this->$key = $value;
		}
	}
	
}

