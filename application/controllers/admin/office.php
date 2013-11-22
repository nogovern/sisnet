<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Office Controller class
 *
 * @package gs25
 * @author Jang KwangHee 
 **/
class Office extends CI_Controller
{
	public function __construct() {
		parent::__construct();

		$this->load->library('doctrine');
	}

	public function index()
	{
		$this->lists();
	}

	public function lists($action = 'lists') {
		$em = $this->doctrine->em;

		$items = $em->getRepository('Entity\Office')->findAll();
		// $data['rows'] = $items;

		$data = array(
			'rows' => $items,
			'title' => '사무소 리스트',
			'page_title' => '사무소 리스트'
			);

		$this->render('office_list.html', $data);
	}

	public function render($view_url, $data = array())
	{
		$this->load->view('layout/header', array('title' => $data['title']));
		$this->load->view('layout/navbar');
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}
	
} // END class Office extends CI_Controller