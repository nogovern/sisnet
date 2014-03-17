<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WorkTransfer_m extends MY_Model {

	public function __construct() {
		parent::__construct();

		$this->setEntityName('Operation');
		$this->setTableName('gs2_operations');
	}

	public function all() {
		return $this->work_model->getOperations(GS2_OP_TYPE_TRANSFER);
	}

}