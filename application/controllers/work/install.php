<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 설치 업무 컨트롤러
*/
class Install extends CI_Controller
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
		$data['title'] = '설치';
		$data['type'] = '';
		$data['rows'] = $this->work_model->getInstallList();


		$this->load->view('work_install_list', $data);
	}

	/**
	 * 업무 내용 상세보기
	 *
	 * @param  integer $work_id 업무 UID
	 * @return void        
	 */
	public function view($work_id = 0) {
		if($work_id == 0) {
			die('에러! 업무 번호는 필수입니다');
		}

		$data['title'] = '설치';

		$work = $this->work_model->get($work_id);
		$data['work'] = $work;
		$data['store'] = $this->work_model->parseLocation($work->work_location);	// 점포 
		$data['items'] = $work->getItemList();
		
		////////////////
		// 요청확정용 
		////////////////
		$this->load->helper('form');
		$this->load->model('office_m', 'office_model');
		$arr_office = $this->work_model->convertForSelect($this->office_model->getList());
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control"');

		$this->load->model('user_m', 'user_model');
		$arr_user = $this->work_model->convertForSelect($this->user_model->getListByType(1));
		$data['select_user'] = form_dropdown('worker_id', $arr_user, 0, 'id="worker_id" class="form-control required"');


		$this->load->view('work_install_view', $data);
	}

	public function add() {
		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '설치 업무';

		// 규칙 설정
		$this->form_validation->set_rules('store_name', '설치 점포', 'required');
		$this->form_validation->set_rules('date_open', '점포 개점일', 'required');
		$this->form_validation->set_rules('date_request', '설치 일시', 'required');
		$this->form_validation->set_rules('office_id', '설치 사무소', 'required|greater_than[0]');

		// 재고 사무소 목록
		$arr_office = array();		
		$arr_office[0] = '-- 선택하세요 --';
		$this->load->model('office_m', 'office_model');
		$rows = $this->office_model->getList();
		foreach($rows as $row) {
			$arr_office[$row->id] = $row->name;
		}

		// selectbox 생성
		$data['select_office'] = form_dropdown('office_id', $arr_office, 0, 'id="office_id" class="form-control required"');

		if($this->form_validation->run() === FALSE) {
			$this->load->view('work_install_add_form', $data);
		} else {
			// var_dump($_POST);
			$post = $this->input->post();
			$post['date_work'] = $this->input->post('date_open');
			
			$this->work_model->createInstallOperation($this->input->post('op_type'), $post);
			alert('설치 요청을 등록하였습니다.', site_url('/work/install'));
			
			// redirect(site_url('/work/install'));
			exit;
		}

	}

	public function open() {
		echo '설치 업무를 시작합니다';
	}

	public function close() {
		echo '설치 업무를 종료합니다';
	}

	public function ajax($action) {
		if(empty($action)){
			echo 'error - $actin 이 비어있음!!!';
		}

		$em = $this->work_model->getEntityManager();

		$id = $_REQUEST['id'];
		$op = $this->work_model->get($id);

		if($action == 'request_ok') {
			// var_dump($_POST);
			
			$worker = $em->getReference('Entity\User', $this->input->post('worker_id'));
			
			$op->setWorker($worker);
			$op->setDateWork($this->input->post('date_work'));
			$op->setStatus('2');
			$op->setDateModify();
			$op->setMemo($this->input->post('memo'));

			$em->persist($op);
			$em->flush();

			echo 'success';
		}
		// 장비 등록
		elseif( $action == 'add_item') {
			$part = $work->getItem()->part;
			$val = $_POST['val'];
			$temp = $this->work_model->addItem($work, $part, $val);
			if(!$temp){
				echo 'error!';
				exit;
			}

			// 수량 비교용 
			$request_qty = $work->getItem()->qty_request;

			$tpl = '<tr data-temp_id="%d">
                    <td>-</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%d</td>
                    <td style="width:150px;">
                      <button class="btn btn-danger btn-xs btn_delete" type="button">X</button>
                    </td>
                  </tr>';
            echo sprintf($tpl, $temp->id, $part->name, $temp->getSerialNumber(), $temp->qty);
            exit;
		}
		// 장비 목록에서 삭제
		elseif( $action == 'remove_item') {
			$item = $em->getReference('Entity\OperationPart', $_POST['part_id']);
			$this->work_model->removeItem($item);

			echo '[Install] remove_item action is done';
		} 

	}

	// 설치 - 장비 등록 modal content load...
	public function loadModalContent() {
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getSubCategories(1);
		$cats = $this->category_model->convertForSelect($cats);
		
		$this->load->helper('form');
		$data['select_category'] = form_dropdown('category_id', $cats, 0, 'id="category_id" class="form-control"');

		$this->load->view('util/modal_part_register', $data);
	}

}
