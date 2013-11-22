<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin\Inventory Controller class
 *
 * @package gs25
 * @author Jang KwangHee 
 **/
class Inventory extends CI_Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$this->lists();
	}


	public function lists()
	{
		$this->load->model('inventory_repository');
		$rows = $this->inventory_repository->getList();

		$data = array(
			'rows' => $rows,
			'title' => 'Sisnet service >> 창고 리스트',
			'page_title' => '창고 리스트'
			);

		$this->render('inventory_list.html', $data);
	}

	public function render($view_url, $data = array())
	{
		$this->load->view('layout/header', array('title' => $data['title']));		
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}
} // END class Admin\Inventory extends CI_Controller