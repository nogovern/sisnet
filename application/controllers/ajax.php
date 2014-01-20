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

	/**
	 * 출고용 장비 모델 리스트 반환
	 * - 담당 사무소의 재고량 도 함께 불러온다 
	 * 
	 * @param  integer $category_id 	
	 * @return string              		select/option html 엘리먼트
	 */
	public function get_model_list_for_delivery($category_id) {
		// 주의 
		// isset($_POST) 하면 항상 TRUE 를 리턴한다.
		if(!empty($_POST['category_id'])) {
			$category_id = $this->input->post('category_id');
		}

		$this->load->model('part_m');

		$em = $this->part_m->getEntityManager();
		$category = $em->getReference("Entity\Category", $category_id);
		$parts = $em->getRepository('Entity\Part')->findBy(array('category' => $category));

		// 특정 사무소의 재고 수량을 얻을 경우
		$office_id  = (isset($_POST['office_id']) && !empty($_POST['office_id'])) ? $_POST['office_id'] : NULL;

		$output = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		if(count($parts)){
			$output .= '<option value="0">--선택하세요--</option>';
			foreach($parts as $p) {
				$disabled = ( $p->getNewTotal($office_id) == 0 && $p->getUsedTotal($office_id) == 0) ? 'disabled' : '';
				$tpl =  '<option value="%d" %s>%s (%d/%d)</option>';
				$output .= sprintf($tpl, $p->id, $disabled, $p->name, $p->getNewTotal($office_id), $p->getUsedTotal($office_id));
			}

			echo $output;
		} else {
			echo '1000';
		}
	}

	/**
	 * 입고용 장비 모델 목록
	 * 
	 * @param  integer $category_id 
	 * @return string              	select/option html 엘리먼트
	 */
	public function get_model_list_for_warehousing($category_id) {
		if(!$category_id) {
			echo 'error - 카테고리 id 가 없음';
			exit;
		}

		$this->load->model('part_m');

		$em = $this->part_m->getEntityManager();
		$category = $em->getReference("Entity\Category", $category_id);
		$parts = $em->getRepository('Entity\Part')->findBy(array('category' => $category));

		$output = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		if(count($parts)){
			$output .= '<option value="0">--선택하세요--</option>';
			foreach($parts as $p) {
				$tpl =  '<option value="%d">%s</option>';
				$output .= sprintf($tpl, $p->id, $p->name);
			}	

			echo $output;
		} else {
			echo 'none';
		}
	}

	// 직전위치로 장비 검색
	public function search_part_by_previous_location($query) {

	}

	// 시리얼로  장비 검색
	public function search_part_by_serial($query) {
		$query = (empty($query)) ? $_POST['input_text'] : $query;
		if(empty($query)) {
			log_message('error', __METHOD__ . ' 시리얼넘버 가 비어있음!');
		}

		$sn = urldecode($query);

		$this->load->model('part_m', 'part_model');
		$result = $this->part_model->existSerialNumber($sn);

		echo ($result) ? 'false' : 'true';
	}

	// 등록된 사용자명인지 검사
	public function is_exist_username() {
		$this->load->model('user_m', 'user_model');

		$user = $this->user_model->getByUsername($this->input->post('username'));

		// jquery validate remote 처리시 
		// false 를 출력해야 error 로 판단
		// username 검색 결과 가 있을 시 false 를 출력해야 함
		echo ($user) ? 'false' : 'true';
	}

	// 등록된 사무소명 이지 검사 
	public function is_exist_office_name($name = '') {

		// uri 에 검색어가 한글일 경우 urldecode 해줘야 하는군...
		$office_name = ($name) ? urldecode($name) : $_POST['name'];

		$this->load->model('office_m', 'office_model');
		$result = $this->office_model->getByName($office_name);

		header('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
		echo (!$result) ? 'true' : 'false';
	}

	// 등록된 점포명인지 검사
	public function is_exist_store_name($name) {
		// uri 에 검색어가 한글일 경우 urldecode 해줘야 하는군...
		$name = !empty($name) ? urldecode($name) : $_POST['name'];

		$this->load->model('store_m', 'store_model');
		$result = $this->store_model->getByName($name);

		echo (!$result) ? 'true' : 'false';

	}

	
}