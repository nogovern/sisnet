<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	리포트 컨트롤러
*/
class Report extends CI_Controller
{
	public function __construct() {
		parent::__construct();

		$this->load->model("work_m", "work_model");
	}

	// 사용자 접속
	public function user() {
		echo "사용자 접속 기록";
	}

	// 작업자별 업무
	public function worker() {
		$data['title'] 		= '작업자별 작업량';
		$data['current']	= 'page-report';


	}

	// 사무소별 업무
	public function office($o_id = 1) {
		$data['title'] 		= '사무소별 작업량';
		$data['current']	= 'page-report';

		$this->load->model('report_m', 'report_model');
		$rows = array();

		$stats = $this->report_model->getStatsByOffice($o_id);
		$stats['total'] = array_sum($stats);
		$stats['name'] = '가산';	// 사무소명
		
		$rows[] = $stats;		
		
		$data['headers'] = $this->report_model->getOperationArray(); 
		$data['rows'] = $rows;
		$data['pagination'] = '';

		$this->load->view('report_office', $data);
	}

}
