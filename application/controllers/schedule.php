<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Schedule extends CI_Controller
{
	public function __construct() {
		parent::__construct();

		$this->load->model('calendar_m');
	}

	public function index()
	{
		$this->calendar();
	}

	public function calendar($year= null, $month=null) {
		if(!$year) {
			$year = date('Y');
		}

		if(!$month) {
			$month = date('m');
		}

		if(!$day = $this->input->post('day')) {
			;
		}

		$data['title'] = '일정';

		$this->load->model('calendar_m');
		$data['calendar'] = $this->calendar_m->generate($year, $month);

		$this->load->view('calendar', $data);	

	}

} // END class Schedule extends CI_Controller
