<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 카테고리 모델
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

	// 장비 있는 카테고리 목록만 
	public function getValidPartCategories() {
		$rows = $this->getAllPartCategories();

		$cats = array();
		foreach($rows as $row) {
			if(count($row->entries)) {
				$cats[] = $row;
			}
		}

		return $cats;
	}

	// 모든 카테고리 목록 
	public function getAllPartCategories() {
		$qb = $this->em->createQueryBuilder();
		$qb->select('c')
			->from('Entity\Category', 'c')
			->where("c.parent = 1");

		$cats = $qb->getQuery()->getResult();

		return $cats;
	}
}