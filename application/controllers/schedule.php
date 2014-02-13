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

		// gs2_dump($this->session->userdata);
	}

	public function calendar($year= null, $month=null) {
		$this->load->helper('form');

		if(!$year) {
			$year = date('Y');
		}

		if(!$month) {
			$month = date('m');
		}

		$data['title'] = '일정';

		$this->load->model('calendar_m');
		$data['calendar'] = $this->calendar_m->generate($year, $month);

		// 업무종류
		$ops = array(
			0		=> '전체',
			'100'	=> '입고',
			'200'	=> '설치',
			'201'	=> '설치-신규',
			'300'	=> '철수',
			'301'	=> '철수-폐점',
			);
		$data['operation_filter'] = form_dropdown('opType', $ops, $this->input->get('opType'), 'id="op_type_filter" class="form-control" ');

		// 재고 사무소
		$this->load->model('office_m', 'office_model');
		$selected_office = ($this->input->get('office') === FALSE) ? $this->session->userdata('office_id') : $this->input->get('office');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$data['office_filter'] = form_dropdown('office', $arr_office, $selected_office, 'id="office_filter" class="form-control"');

		$this->load->view('calendar', $data);	
	}

} // END class Schedule extends CI_Controller
