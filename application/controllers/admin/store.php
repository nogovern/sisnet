<?php 
/**
 * Store 컨트롤러
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends CI_Controller {
	public function __construct()
	{
		parent::__construct();

		// load User model...
		$this->load->model('store_m', 'store_model');

		// 프로파일링 설정
		//$this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	// 신규 점포 등록 alias
	public function add($mode = NULL) {
		$this->register($mode);
	}

	/**
	 * 신규 점포 등록
	 * 
	 * @param  string $mode 	popup 이면 팝업 형태 등록 폼 처리
	 * @return void
	 */
	public function register($mode = NULL){
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '점포 등록';

		// popup창에서 등록 시 DB에 저장 후 행동을 하기 위해 추가
		if($mode == 'popup') {
			$data['form_saved'] = FALSE;
			$data['new_store_id'] 	= NULL;
			$data['new_store_name'] = NULL;
		}

		// 규칙 설정
		$this->form_validation->set_rules('name', '점포명', 'required');

		if($this->form_validation->run() === FALSE){
			if($mode == 'popup') {
				$this->load->view('store_add_popup_form', $data);
			} else {
				$this->load->view('store_add_form', $data);
			}

		} else {
			// var_dump($_POST);
			$store = $this->store_model->create($this->input->post());
			
			// 일반적인 등록일 경우
			if($mode === NULL) {
				redirect('/admin/store');
			}
			// 팝업창에서 등록일 경우, 등록 후 처리 위해 
			else {
				$data['form_saved'] 	= TRUE;
				$data['new_store_id'] 	= $store->id;
				$data['new_store_name'] = $store->name;

				// var_dump($data);
				$this->load->view('store_add_popup_form', $data);				
			}

		}
	}

	public function edit($id) {
		if(empty($id)){
			alert("올바르지 않은 접근입니다");
			exit;
		}


	}

	public function lists() {

		$data['title'] = '점포 리스트';
		$data['current'] = 'page-admin-store';
		$data['rows'] = $this->store_model->getList();

		$this->load->view('store_list', $data);
	}

	public function ajax($action) {
		$this->load->model('store_m', "store_model");

		// colorbox 프레임 방식 로드함
		// 점포명으로 검색하여 리턴 함
		if($action == 'search') {
			// 점포명
			$q = $this->input->post('query');
			if(!$q) {
				alert_colorbox_close('점포명을 입력하세요');
			}

			$data['title'] = "점포명으로 검색";

			// javascript encodeURIComponent() 했으므로 decode 해야지 한글 검색 가능함!
			$q = urldecode($q);		
			$rows = $this->store_model->findByName($q);

			$output = '';
			$status_text = array('페점', '정상', '휴점C', '휴점S' );
			foreach($rows as $row) {
				$format = "<tr>
	              <td>%d</td>
	              <td>%s</td>
	              <td>%s</td>
	              <td>%s</td>
	              <td>%s</td>
	              <td>%s</td>
	              <td>%s</td>
	            </tr>";

	            $output .= sprintf($format, 
	            	$row->id, 
	            	$row->name, 
	            	$row->owner_name, 
	            	$row->address, 
	            	$row->tel,
	            	$status_text[$row->status],
	            	'<a href="#" class="select_me">[선택]</a>'
	            );
	        }

	        echo $output;
		}

	}

}

