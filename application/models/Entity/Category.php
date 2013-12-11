<?php
namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table(name="GS2_CATEGORIES")
 */
class Category
{
	/**
	 * @Id @Column(type="integer", nullable=false)
	 * @GeneratedValue(strategy="AUTO")
	 * @SequenceGenerator(sequenceName="gs2_category_seq")
	 */
	protected $id;

	/** 
	 * @OneToOne(targetEntity="Category")
	 * @JoinColumn(name="parent_id", referencedColumnName="id")
	 */
	protected $parent;

	/** @Column(type="string", length=50) */
	protected $name;
	
	/** @Column(type="datetime") */
	protected $date_register;
	
	/** @Column(type="string", length=1) */
	protected $status;

	// --------------------------------------------
	public function __construct() {
		;
	}
	
	public function __get($key) {
		return $this->$key;
	}

	public function __set($key, $value) {
		if(property_exists($this, $key)) {
			$this->$key = $value;
		}
	}

}

