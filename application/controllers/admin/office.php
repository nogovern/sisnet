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
	}

	public function index()
	{
		$this->lists();
	}

	public function lists($action = 'lists') {
		$this->load->model('office_repository');
		$rows = $this->office_repository->getList();

		$data = array(
			'rows' => $rows,
			'title' => '사무소 리스트',
			'page_title' => '사무소 리스트'
			);

		$this->render('office_list.html', $data);
	}

	public function render($view_url, $data = array())
	{
		$this->load->view('layout/header', array('title' => $data['title']));		
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}
	
} // END class Office extends CI_Controller