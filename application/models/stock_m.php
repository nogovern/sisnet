<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 재고 모델
*/
class Stock_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_stocks');
		$this->setEntityName('Stock');
	}
}