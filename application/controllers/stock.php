<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 재고 컨트롤러
*/
class Stock extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->library('doctrine');
	}

	public function index() {
		$this->lists();
	}

	public function lists() {

		$data['title'] = '재고------------------^';
		$data['rows'] = $this->_lists();

		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('stock_list', $data);
		$this->load->view('layout/footer');
	}

	private function _lists() {
		$em = $this->doctrine->em;

		$data = array();

		$parts = $em->getRepository('Entity\Part')->findAll();
		// foreach($parts as $part) {
		// 	$stocks = $part->getStockList();
		// 	echo count($stocks);
		// }

		return $parts;
	}
}