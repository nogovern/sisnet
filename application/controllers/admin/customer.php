<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
		echo 'hi there';
	}

	public function add() {
		$this->output->enable_profiler(TRUE);

		$this->load->library('doctrine');
		$em = $this->doctrine->em;

		if(0) {
			$customer = new Entity\Customer();
			$customer->name = "IBM Corp";
			$customer->code = "IBM";
			$customer->type = "1";
			// $customer->date_register = "SYSDATE";

			$em->persist($customer);
			$em->flush();
		}

		echo __METHOD__;

		// print_r($customer);
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

}