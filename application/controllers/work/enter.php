<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 입고 컨트롤러
*/
class Enter extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->library(array('doctrine', 'auth'));
		$this->em = $this->doctrine->em;
	}

	public function index() {
		$this->main();
	}

	public function main() {
		$rows = $this->em->getRepository('Entity\Operation')->findAll();

		foreach($rows as $row) {
			var_dump($row->no);
			var_dump($row->office->name);
			var_dump($row->user->name);
		}
	}
}
