<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	공통 Ajax 응답 컨트롤러
*/
class Ajax extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
		show_404();
	}

	public function part_select() {

	}

	public function request_enter_form($part_id) {
		if(empty($part_id)){
			trigger_error("장비 ID는 필수입니다.");
			exit;
		}

		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '장비 입고 요청서';

		$this->load->view('popup_request_enter_form', $data);
	}

	// 전체 장비 목록
	public function response($id) {
		// 주의 
		// isset($_POST) 하면 항상 TRUE 를 리턴한다.
		if(!empty($_POST['category_id'])) {
			$id = $this->input->post('category_id');
		}

		$this->load->model('part_m');

		$em = $this->part_m->getEntityManager();
		$category = $em->getReference("Entity\Category", $id);
		$parts = $em->getRepository('Entity\Part')->findBy(array('category' => $category));

		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		if(count($parts)){
			echo '<option value="0">--선택하세요--</option>';
			foreach($parts as $p) {
				echo '<option value="' . $p->id . '">'.  $p->name.'</option>';
			}
		} else {
			echo '1000';
		}

	}

	// 창고의 장비 재고 목록
	public function response_1() {

	}

}