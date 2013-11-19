<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin Controller class
 *
 * @package gs25
 * @author Jang KwangHee 
 **/
class Admin extends CI_Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		echo '<h1>관리자 페이지 Index 입니다</h1>';
	}

	public function office($action = 'lists') {
		$this->load->model('office_repository');
		$rows = $this->office_repository->getList();

		$data = array(
			'rows' => $rows,
			'title' => '사무소 리스트',
			'page_title' => '사무소 리스트'
			);

		$this->render('office_list.html', $data);
	}

	public function inventory($action = 'lists')
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

	public function user($action = 'lists') {

	}

	/**
	 * 고객사
	 * @param  string $action
	 * @return [type]
	 */
	public function customer($action = 'lists')
	{
		# code...
	}

	/**
	 * view template method - view 페이지 url 을 받아 header, footer 를 붙여 출력
	 * @param  [type] $view_url
	 * @param  array  $data
	 * @return [type]
	 */
	public function render($view_url, $data = array())
	{
		$this->load->view('layout/header', array('title' => $data['title']));		
		$this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}

} // END class Admin extends CI_Controller