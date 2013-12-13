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

		$this->load->model('inventory_m', 'inventory_model');
	}

	public function index()
	{
		$this->lists();
	}


	public function lists()
	{

		$data = array(
			'rows' => $this->inventory_model->getList(),
			'title' => 'Sisnet service >> 창고 리스트',
			'page_title' => '창고 리스트'
			);

		$this->render('inventory_list.html', $data);
	}

	public function render($view_url, $data = array())
	{
		$this->load->view('layout/header', array('title' => $data['title']));
		$this->load->view('layout/navbar', $data);
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}
} // END class Admin\Inventory extends CI_Controller