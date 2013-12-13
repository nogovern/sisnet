<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Test_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->db = $this->load->database('oracle', TRUE);

		echo 'Test_model is loaded...';
	}

}