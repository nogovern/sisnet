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
	public function request() {
		$data['title'] = "입고 요청 작성";

		$this->load->library('form_validation');
		$this->load->helper('form');

		$this->load->view('work_enter_add_form', $data);
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

		$part_id = @$_REQUEST['part_id'];
		$office_id = @$_REQUEST['office_id'];

		if(empty($part_id) || empty($office_id)) {
			$this->load->helper('alert');
			alert_colorbox_close('에러!');		// alert helper
		}

		$data['title'] = "입고 요청 작성";

		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['form_hiddens'] = array(
				'work_type'	=> GS2_OPERATION_TYPE_ENTER,
				'user_id'	=> '8',
				'part_id'	=> $part_id,
				'office_id'	=> $office_id
			);


		// 규칙 설정
		$this->form_validation->set_rules('qty', '수량', 'required|greater_than[0]');
		$this->form_validation->set_rules('date_request', '입고 희망일', 'required');

		////////////
		// 폼 검증 
		////////////
		if($this->form_validation->run() === FALSE) {
			$em = $this->work_model->getEntityManager();
			$office = $em->getRepository('Entity\Office')->find($office_id);
			$part = $em->getRepository('Entity\Part')->find($part_id);

			$data['office_name'] = $office->name;
			$data['part_name'] = $part->name;

			$this->load->view('popup_request_enter_form', $data);

		} else {
			var_dump($_POST);

			// 100 - 입고업무
			$this->work_model->register(GS2_OPERATION_TYPE_ENTER, $this->input->post());
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

	public function view($work_id) {
		$data['title'] = '입고 요청 보기';

		$work = $this->work_model->get($work_id);
		$data['work'] = $work;

		$this->load->view('work_enter_view', $data);
	}

	public function view2($work_id) {
		$data['title'] = '입고 요청 보기';

		$work = $this->work_model->get($work_id);
		$data['work'] = $work;

		$this->load->view('work_enter_view2', $data);
	}


	////////////////
	// ajax 요청 처리 
	////////////////
	public function ajax($action) {
		if(empty($action)){
			echo 'error - $actin 이 비어있음!!!';
		}

		$id = $_REQUEST['id'];
		$work = $this->work_model->get($id);

		// 요청 -> 요청확정 단계로 이동
		if($action == 'request_ok') {

			$work->setStatus('2');
			$work->setDateModify();

			$this->work_model->_add($work);
			$this->work_model->_commit();

			echo 'success';
		} 
		// 납품처 장비 등록
		elseif( $action == 'temp_add') {
			$part = $work->getItem()->part;
			$val = $_POST['val'];
			$temp = $this->work_model->addTempItem($work, $part, $val);
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
		elseif( $action == 'temp_delete') {
			echo $_POST['temp_id'];
		} 
		// 출고 완료
		elseif( $action == 'delivery') {
			var_dump($_POST);
			echo '출고 상태로 변경 하였음!';
		}
		// 장비 확인
		elseif($action == 'inspect') {
			echo '받은 장비 확인중';
		}
		// 업무 종료
		elseif($action == 'complete') {
			echo '입고 업무를 종료함';

		} else {
			echo '[error] 등록되지 않은 요청임다!';
		}
	}

}
