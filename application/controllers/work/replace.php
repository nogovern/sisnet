<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	교체 컨트롤러
*/
class Replace extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists() {
		$data['title'] = '교체 업무';
		$data['current'] = 'page-replace';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getReplaceList();
		
		$this->load->view('work/work_replace_list', $data);
	}

	/////////////
	// 요청서 등록
	/////////////
	public function register() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '교체 업무 >> 요청서 등록';
		$data['current'] = 'page-replace';

		// 사무소 select 생성
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = gs2_convert_for_dropdown($rows);
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control required"');

		// 규칙 설정		
		$this->form_validation->set_rules('office_id', '담당 사무소', 'required|greater_than[0]');
		$this->form_validation->set_rules('store_id', '교체 점포', 'required');
		$this->form_validation->set_rules('store_name', '교체 점포명', 'required');
		$this->form_validation->set_rules('date_open', '설치 요청일', 'required');
		$this->form_validation->set_rules('date_close', '철수 요청일', 'required');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('work/work_replace_register', $data);

		} else {
			// gs2_dump($_POST);
			$post_data = $this->input->post();

			// 설치 업무 생성
			$post_data['op_type'] = '205';
			$post_data['date_request'] = sprintf("%s %02d:00:00", $post_data['date_open'], $post_data['date_open_hour']);
			$install_op = $this->work_model->createOperation('205', $post_data, TRUE);
			
			// 철수 업무 생성
			$post_data['op_type'] = '305';
			$post_data['date_request'] = sprintf("%s %02d:00:00", $post_data['date_close'], $post_data['date_close_hour']);
			$close_op = $this->work_model->createOperation('305', $post_data, TRUE);

			// 교체 업무 생성
			$post_data['op_type'] = '400';
			$op = $this->work_model->createOperation('400', $post_data);

			$tg1 = $this->work_model->createTargetOperation($op, $install_op);
			$tg2 = $this->work_model->createTargetOperation($op, $close_op);

			//////////////////////////
			// 첨부 파일 업로드
			//////////////////////////
			$this->load->library('upload');
			$this->load->model('file_m', 'file_model');

			$files = $_FILES;
			$file_count = count($files['userfile']['name']);

			for($i=0; $i < $file_count; $i++) {
				$_FILES['userfile']['name']= $files['userfile']['name'][$i];
		        $_FILES['userfile']['type']= $files['userfile']['type'][$i];
		        $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
		        $_FILES['userfile']['error']= $files['userfile']['error'][$i];
		        $_FILES['userfile']['size']= $files['userfile']['size'][$i];

		        if($_FILES['userfile']['error'] == 0 && $_FILES['userfile']['size'] > 0) {
		        	$this->upload->initialize($this->file_model->setUploadOption());
		        	// 업로드 성공시 
		        	if($this->upload->do_upload() === FALSE) {
		        		$upload_error = TRUE;
		        		
		        	} else {
		        		$f_data = $this->upload->data();

		        		//////////////
		        		//  업로드 저장 배열에 추가 정보를 더해 데이터 넘겨준다
		        		//////////////
		        		$f_data['gubun'] = '요청';
		        		$f_data['op_id'] = $op->id;

		        		$this->file_model->create($f_data);
		        	}
		        }
			}

			// doctrine flush 실행
			$this->work_model->_commit();

			if(isset($upload_error) && $upload_error == TRUE) {
				alert("파일 업로드 중 에러가 발생했습니다\nerror: " . $this->upload->display_errors());
			} else {
				$this->work_model->_commit();
				alert('설치 요청을 등록하였습니다.', site_url('/work/replace'));
			}
		}
	}

	public function view($id) {

		$data['title'] = '교체업무 > 상세보기';
		$data['current'] = 'page-replace';

		$work = $this->work_model->get($id);
		$data['work'] = $work;
		$data['_config'] = $this->config->item('gs2');

		////////////////
		// 요청확정용 
		////////////////
		$this->load->helper('form');

		// 사무소 dropdown
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$data['select_office'] = form_dropdown('office_id', $arr_office, $work->office->id, 'id="office_id" class="form-control"');

		// 사용자 dropdown
		$this->load->model('user_m', 'user_model');
		$arr_user = gs2_convert_for_dropdown($this->user_model->getListByType(1));
		$data['select_install_user'] = form_dropdown('install_worker_id', $arr_user, 0, 'id="install_worker_id" class="form-control required"');
		$data['select_close_user'] = form_dropdown('close_worker_id', $arr_user, 0, 'id="close_worker_id" class="form-control required"');

		// 장비 카테고리 dropdown
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getSubCategories(1);
		$cats = gs2_convert_for_dropdown($cats);
		$data['select_category'] = form_dropdown('category_id', $cats, 0, 'id="category_id" class="form-control"');

		$this->load->view('work/work_replace_view', $data);

	}

	public function update($id) {
		echo '작업중';
	}

}