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
				'work_type'	=> GS2_OP_TYPE_ENTER,
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
			$this->work_model->register(GS2_OP_TYPE_ENTER, $this->input->post());
			echo 'done';
			exit;
		}
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

		$em = $this->work_model->getEntityManager();

		$data['title'] = '입고 요청 보기';

		$work = $this->work_model->get($work_id);
		$data['work'] = $work;
		$data['temp_items'] = $em->getRepository('Entity\OperationTempPart')->findBy(array('operation' => $work));

		if($work->status >= "3") {
			$this->load->view('work_enter_scan_view', $data);
		} else {
			$this->load->view('work_enter_view', $data);
		}

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
		// 납품처 - 장비 목록에서 삭제
		elseif( $action == 'temp_delete') {
			$temp_obj = $this->work_model->em->getReference('Entity\OperationTempPart', $_POST['temp_id']);
			$this->work_model->removeTempItem($temp_obj);

			echo 'OK';
		} 
		// 출고 완료
		elseif( $action == 'delivery') {
			$work->setStatus('3');
			$work->setDateModify();

			$this->work_model->_add($work);
			$this->work_model->_commit();

			echo '출고 상태로 변경 하였음!';
		}
		// 스캔 저장
		elseif($action == 'scan_save') {
			$items = explode(",", $_POST['items']);
			foreach($items as $sn) {
				$temp = $this->work_model->em->getRepository('Entity\OperationTempPart')
												->findBy(array('operation' => $work, 'serial_number' => $sn));
				if($temp[0]){
					$temp[0]->setScanFlag(TRUE);
					$temp[0]->setQuantity(1);

					$this->work_model->em->persist($temp[0]);
				}
			}

			$this->work_model->_commit();

			echo '스캔 결과 저장';
		}
		// 수량 확인 저장
		elseif($action == 'save_count') {
			
			$work->setStatus('4');
			$work->setDateModify();

			$temps = $this->work_model->em->getRepository('Entity\OperationTempPart')->findBy(array('operation' => $work));
			foreach($temps as $temp) {
				$temp->setScanFlag(TRUE);
				$temp->setQuantity($_POST['cnt']);

				$this->work_model->_add($temp);
			}

			$this->work_model->_add($work);
			$this->work_model->_commit();

			echo '수량 : ' . $_POST['cnt'];
		}
		// 업무 종료
		elseif($action == 'complete') {
			$this->load->model('part_m', 'part_model');

			// 업무 메인 
			$work->setStatus('4');
			$work->setDateModify();

			$this->work_model->_add($work);

			// 임시 장비 목록
			$complete_count = 0; 
			$temps = $this->work_model->em->getRepository('Entity\OperationTempPart')->findBy(array('operation' => $work));
			$idx = 0;
			foreach($temps as $temp) {
				if ($temp->isScan()) { 
					$temp->setCompleteFlag(TRUE);
					$complete_count += $temp->qty;
				}
				$this->work_model->_add($temp);

				// 시리얼 관리 장비 등록
				if($temp->part_type == '1') {
					$location_string = 'O@' . $work->office->id;

					$data = array(
						'part'		=> $temp->part,
						'part_id'	=> $temp->part->id,
						'serial_number'		=> $temp->serial_number,
						'previous_location'	=> '',
						'current_location'	=> $location_string,
						'is_valid'	=> TRUE,
						'is_new'	=> TRUE,
						'date_enter'=> 'now',
						'memo'	=> '입고 업무로 들어 왔어',
						);
					$new = $this->part_model->addSerialPart($data);
				} 
				// 수량, 소모품 재고 변경
				else 
				{
					$stock = $work->office->in($temp->part, $temp->qty, 'new');
					$this->work_model->_add($stock);
				}				 
			}

			// 업무 장비 목록
			$item = $work->getItem();
			$item->setQtyComplete($complete_count);
			$item->setDateModify();
			$item->setCompleteFlag(TRUE);

			$this->work_model->_add($item);

			////////////
			// Last 
			////////////
			$this->work_model->_commit();
			
			echo  '완료 : '. $complete_count .'\\n입고 업무를 종료함';

		} else {
			echo '[error] 등록되지 않은 요청임다!';
		}

		exit;
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
