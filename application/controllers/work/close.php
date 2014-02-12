<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 철수 컨트롤러
*/
class Close extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		// 로그인 확인
		if(!$this->auth->isLoggedIn()) {
			$this->load->helper('alert');
			alert('로그인 하셔야 합니다', site_url('/'));
		}

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists() {
		$data['title'] = '철수 >> 업무 목록';
		$data['current'] = 'page-close';
		$data['type'] = '';
		
		$data['rows'] = $this->work_model->getCloseList();
		$this->load->view('work/work_close_list', $data);
	}

	// 철수 요청
	public function request() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '철수 >> 요청서 작성';
		$data['current'] = 'page-close';

		// 테스트용 -- 추후 삭제
		// 등록 유저는 세션 정보 사용하면 됨 
		$data['user_id'] = $this->session->userdata('user_id');

		// 규칙 설정
		$this->form_validation->set_rules('office_id', '설치 사무소', 'required|greater_than[0]');
		$this->form_validation->set_rules('store_id', '설치 점포', 'required');
		$this->form_validation->set_rules('date_close', '점포 폐점일', 'required');
		$this->form_validation->set_rules('date_request', '설치 일시', 'required');

		// 담당 사무소 목록
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = gs2_convert_for_dropdown($rows);

		// selectbox 생성
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control required"');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('work/work_close_add_form', $data);
		} else {
			$post = $this->input->post();
			$post['date_store'] = $this->input->post('date_close');
			
			// 작업요청일에 시간 포맷 추가
			$post['date_request'] .= sprintf(" %02d:00:00", $post['date_request_hour']);
			
			$op = $this->work_model->createOperation($this->input->post('op_type'), $post);

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
				alert('철수 요청을 등록하였습니다.', site_url('/work/close'));
			}
		}
	}

	// 철수 요청
	public function add() {
		$this->request();
	}

	/**
	 * 철수 업무 상세보기
	 * 
	 * @param  integer $id 		업무 ID (operation.id )
	 * @return void
	 */
	public function view($id) {
		if(!$id) {
			die('에러! 업무 번호는 필수입니다');
		}

		$data['title'] = "철수 업무 상세 보기";
		$data['current'] = 'page-close';
		$data['_config'] = $this->config->item('gs2');

		$work = $this->work_model->get($id);
		$data['work'] = $work;
		$data['store'] = gs2_decode_location($work->work_location);	// 점포 
		$data['items'] = $work->getItemList();
		
		// 휴점점검 - 점포 보관 장비 목록
		if($work->type == '304') {
			$this->load->model('store_m', 'store_model');
			$data['store_items'] = $this->store_model->getStoreItems($work->id);
		}

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
		$data['select_user'] = form_dropdown('worker_id', $arr_user, 0, 'id="worker_id" class="form-control required"');

		// 장비 카테고리 dropdown
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getSubCategories(1);
		$cats = gs2_convert_for_dropdown($cats);
		$data['select_category'] = form_dropdown('category_id', $cats, 0, 'id="category_id" class="form-control"');
		// 휴점-점검용
		$data['select_category2'] = form_dropdown('select_cat', $cats, 0, 'id="select_cat" class="form-control"');

		// 담당자 변경용 dropdown
		$workers = $this->user_model->getOfficeUsers();
		$this_worker = ($work->worker) ? $work->worker->id : 0;
		$data['select_worker'] = form_dropdown('select_worker', $workers, $this_worker, ' id="select_worker" class="form-control"');

		$this->load->view('work/work_close_view', $data);
	}

}
