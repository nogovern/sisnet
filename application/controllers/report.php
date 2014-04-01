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
	public function worker($id = 1) {
		$data['title'] 		= '작업자별 작업량';
		$data['current']	= 'page-report';

		$this->load->model('user_m', 'user_model');
		// 작업자 사무소, 이름순 정렬
		$users = $this->user_model->getListByType(GS2_USER_TYPE_SISNET, array('office' => 'asc', 'name' => 'asc'));

		$this->load->model('report_m', 'report_model');
		$rows = array();

		// 기간 검색 조건
		$today = new DateTime();
		$fromDate = $this->input->get('fromDate') ? $this->input->get('fromDate') : $today->format("Y-m-01");
		$toDate = $this->input->get('toDate') ? $this->input->get('toDate') : $today->format("Y-m-d");

		foreach($users as $u) {
			$stats = $this->report_model->getStatsByWorker($u->id, $fromDate, $toDate);
			$stats['total'] = array_sum($stats);
			$stats['name'] = $u->name;	// 유저명
			
			$rows[] = $stats;		
		}

		// gs2_dump($rows);
		$data['fromDate'] 	= $fromDate;
		$data['toDate']		= $toDate;

		$data['headers'] = $this->report_model->getOperationArray(); 
		$data['rows'] = $rows;
		$data['pagination'] = '';

		$this->load->view('report_worker', $data);
	}

	// 사무소별 업무
	public function office() {
		$data['title'] 		= '사무소별 작업량';
		$data['current']	= 'page-report';

		$this->load->model('report_m', 'report_model');

		// 사무소 목록 가져오기
		$this->load->model('office_m', 'office_model');
		$offices = $this->office_model->getList(array('name' => 'asc'));

		// 기간 검색 조건
		$today = new DateTime();
		$fromDate = $this->input->get('fromDate') ? $this->input->get('fromDate') : $today->format("Y-m-01");
		$toDate = $this->input->get('toDate') ? $this->input->get('toDate') : $today->format("Y-m-d");

		$rows = array();
		foreach($offices as $o) {
			$stats = $this->report_model->getStatsByOffice($o->id, $fromDate, $toDate);
			$stats['total'] = array_sum($stats);
			$stats['name'] = $o->name;	// 사무소명
			
			$rows[] = $stats;		
		}
		
		$data['fromDate'] 	= $fromDate;
		$data['toDate']		= $toDate;
		
		$data['headers'] = $this->report_model->getOperationArray(); 
		$data['rows'] = $rows;
		$data['pagination'] = '';

		$this->load->view('report_office', $data);
	}

}
