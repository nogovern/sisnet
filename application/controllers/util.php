<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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

		// colorbox 프레임 방식 로드함
		// 점포명으로 검색하여 리턴 함
		if($func == 'search') {
			// 점포명
			$q = $this->uri->segment(4);
			if(!$q) {
				alert_colorbox_close('점포명을 입력하세요');
			}

			$data['title'] = "점포명으로 검색";
			// javascript encodeURIComponent() 했으므로 decode 해야지 한글 검색 가능함!
			$q = urldecode($q);		

			$rows = $this->store_model->findByName($q);
			if(!count($rows)) {
				alert_colorbox_close('해당 점포명으로 검색 결과가 없음\n다시 검색해봐');
				exit;
			}
			$data['rows'] = $rows;

			$this->load->view('util/store_search_result', $data);

		}
	}

	// 장비
	public function part($action = '') {
		if(empty($action)) {
			alert('에러!');
		}

		$this->load->model('part_m', 'part_model');

		if($action == 'get') {

			$part = $this->part_model->get($_POST['part_id']);
			
			$json = new stdClass;
			$json->name = $part->name;
			$json->type = $part->type;
			$json->id = $part->id;

			echo json_encode($json);

			exit;
		}

	}
}