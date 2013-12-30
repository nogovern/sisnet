<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	Util 컨트롤러
*/
class Util extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
		show_404();
	}

	// 점포 기능들
	public function store($func = '') {
		if(empty($func)) {
			alert('에러!');
		}

		$this->load->model('store_m', "store_model");

		// colorbox 프레임 방식 로드
		if($func == 'search') {
			// 점포명
			$q = $this->uri->segment(4);
			if(!$q) {
				alert_colorbox_close('점포명을 입력하세요');
			}

			$data['title'] = "점포명으로 검색";

			$rows = $this->store_model->findByName($q);
			if(!count($rows)) {
				alert_colorbox_close('해당 점포명으로 검색 결과가 없음\n다시 검색해봐');
				exit;
			}
			$data['rows'] = $rows;

			$this->load->view('util/store_search_result', $data);

		}
	}
}