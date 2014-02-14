<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 설치 컨트롤러
*/
class Install extends CI_Controller
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
		$data['title'] = '설치 >> 업무 리스트';
		$data['current'] = 'page-install';

		$data['type'] = '';
		$data['rows'] = $this->work_model->getInstallList();


		$this->load->view('work_install_list', $data);
	}

	/**
	 * 업무 내용 상세보기
	 *
	 * @param  integer $id 업무 UID
	 * @return void        
	 */
	public function view($id = 0) {
		if($id == 0) {
			die('에러! 업무 번호는 필수입니다');
		}

		$data['title'] = '설치 >> 상세보기';
		$data['current'] = 'page-install';

		$data['_config'] = $this->config->item('gs2');

		$work = $this->work_model->get($id);
		$data['work'] = $work;
		$data['store'] = gs2_decode_location($work->work_location);	// 점포 
		$data['items'] = $work->getItems();
		
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
		$data['select_category'] = form_dropdown('select_category', $cats, 0, 'id="select_category" class="form-control"');

		// 담당자 변경용 dropdown
		$this_worker = ($work->worker) ? $work->worker->id : 0;
		$workers = $this->user_model->getOfficeUsers();
		$data['select_worker'] = form_dropdown('select_worker', $workers, $this_worker, ' id="select_worker" class="form-control"');

		$this->load->view('work_install_view', $data);
	}

	///////////////
	// 설치 요청 등록
	///////////////
	public function add() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '설치 >> 요청서 작성';
		$data['current'] = 'page-install';

		// 규칙 설정
		$this->form_validation->set_rules('store_name', '설치 점포', 'required');
		$this->form_validation->set_rules('date_open', '점포 개점일', 'required');
		$this->form_validation->set_rules('date_request', '설치 일시', 'required');
		$this->form_validation->set_rules('office_id', '설치 사무소', 'required|greater_than[0]');

		// 사무소 목록
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		$arr_office = gs2_convert_for_dropdown($rows);

		// selectbox 생성
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control required"');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('work_install_add_form', $data);
		} else {
			$post = $this->input->post();
			$post['date_store'] = $this->input->post('date_open');

			// 작업요청일에 시간 포맷 추가
			$post['date_request'] .= sprintf(" %02d:00:00", $post['date_request_hour']);
			
			// 설치 업무 생성
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
				alert('설치 요청을 등록하였습니다.', site_url('/work/install'));
			}
		}
	}

	public function open() {
		echo '설치 업무를 시작합니다';
	}

	public function close() {
		echo '설치 업무를 종료합니다';
	}

	// 설치 - 장비 등록 modal content load...
	public function loadModalContent() {
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getSubCategories(1);
		$cats = gs2_convert_for_dropdown($cats);
		
		$this->load->helper('form');
		$data['select_category'] = form_dropdown('category_id', $cats, 0, 'id="category_id" class="form-control"');

		$this->load->view('common/modal_part_register', $data);
	}

	/////////////////
	// ajax 요청 처리
	/////////////////
	public function ajax($action) {
		if(empty($action)){
			echo 'error - $actin 이 비어있음!!!';
		}

		$em = $this->work_model->getEntityManager();

		// var_dump($this->uri->segment(5));
		$id = $_REQUEST['id'];
		$op = $this->work_model->get($id);

		// ============= 삭제함 ============
		// 공통 루틴이라 /work/ajax/accept_request 로 변경함
		// 
		// if($action == 'request_ok') {
		// }
		// ================================
		
		// 점포 완료
		// work/ajax/store_complete 로 대체 함
		
		// 설치 업무 완료
		if( $action == 'operation_complete') {
			// 업무 log 생성
			$log_data = array(
				'content' => '작업을 종료합니다',
				'date_complete' => $this->input->post('date_complete'),
				'type' => '1',
				);
			$this->work_model->addLog($op, $log_data);

			// 업무 상태 변경
			$this->work_model->nextStatus($op);
			
			// 장비 출고 후 재고 반영
			$this->work_model->deliveryItem($op);

			// 점포 상태 변경
			

			// 실제 DB 반영
			$this->work_model->_commit();
			
			echo $action . ' is done.';
		}

	}// end of ajax
}
