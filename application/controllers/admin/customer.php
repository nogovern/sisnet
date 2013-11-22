<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		$this->load->library('doctrine');
		
		// 프로파일링 설정
		$this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	public function add() {
		$em = $this->doctrine->em;		

		if(1) {
			$customer = new Entity\Customer();
			$customer->name = "IBM Corp";
			$customer->code = "IBM";
			$customer->type = "1";
			$customer->date_register = new DateTime();
			// $customer->date_register = "2013-11-11 00:00:00";

			$em->persist($customer);
			$em->flush();
		}

	}

	public function lists() {
		$em = $this->doctrine->em;

		$items = $em->getRepository('Entity\Customer')->findAll();

		print_r($items);

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