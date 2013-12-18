<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 입고 컨트롤러
*/
class Enter extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
		$this->em = $this->work_model->getEntityManager();

		$this->load->library('auth');
	}

	public function index() {
		$this->main();
	}

	public function main() {

		$data['title'] = '입고업무';
		$data['type'] = '';
		$data['rows'] = $this->work_model->getEnterList();
		
		$this->load->view('work_enter_list', $data);
	}

	// 입고요청
	public function add() {
		$data['title'] = "입고 요청 작성";

		$this->load->library('form_validation');
		$this->load->helper('form');

		$this->load->view('work_enter_add_form', $data);
	}

	public function request() {
		$this->add();
	}

	// 납품처 기능 
	public function check_request() {
		;
	}

	/**
	 * 재고리스트에서 장비 입고요청
	 */
	public function order_part() {
		// 검색 시 $_GET 을 사용하면 되겠네...
		if(0) {
			echo "<pre>";
			var_dump($_GET);
			echo "</pre>";

			exit;
		}

		$part_id = @$_GET['part_id'];
		$office_id = @$_GET['office_id'];

		if(empty($part_id) || empty($office_id)) {
			$this->load->helper('alert');
			alert_colorbox_close('에러!');		// alert helper
		}

		$data['title'] = "입고 요청 작성";

		$this->load->library('form_validation');
		$this->load->helper('form');

		$hidden_array = array(
				'work_type'	=> '101',
				'part_id'	=> '1',
				'office_id'	=> '1',
				'user_id'	=> '8'
			);


		// 규칙 설정
		$this->form_validation->set_rules('qty', '수량', 'required|greater_than[0]');
		$this->form_validation->set_rules('date_expected', '입고 희망일', 'required');

		////////////
		// 폼 검증 
		////////////
		if($this->form_validation->run() === FALSE) {
			$this->load->view('popup_request_enter_form', $data);

		} else {
			echo 'done';
			exit;
		}
	}


	public function _remap($method, $params = array()) {
		if(!$method) 
			$method = 'main'; 
		
		if(method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}

		show_404();
	}

}
