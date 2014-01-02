<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Category_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_categories');
		$this->setEntityName('Category');
	}

	// 하위 카테고리 목록
	public function getSubCategories($parent_id) {
		$rows = $this->repo->findBy(array('parent' => $parent_id), array('id' => 'asc'));
		return $rows;
	}
}