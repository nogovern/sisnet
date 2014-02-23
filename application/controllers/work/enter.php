<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 입고 컨트롤러
*/
class Enter extends CI_Controller
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
		$data['title'] = '입고업무';
		$data['current'] = 'page-enter';

		///////////////
		// 검색 조건
		///////////////
		$criteria = array();
		
		// 상태
		if($this->input->get('status')) {
			$criteria['status'] = $this->input->get('status');
		}

		// 사무소 - GET 유무 확인시 없을떄 false 로 타입까지 비교해야 함
		if($this->input->get('off_id') === false) {
			$criteria['office'] = (gs2_user_type() == '1') ? $this->session->userdata('office_id') : 0;
		} else {
			$criteria['office'] = $this->input->get('off_id');
		}

		// 장비 종류
		if($this->input->get('cat_id') !== false){
			$criteria['category'] = $this->input->get('cat_id');
		}

		// 장비 모델 
		if($this->input->get('part_id') !== false){
			$criteria['part'] = $this->input->get('part_id');
		}

		// 목록
		$data['rows'] = $this->work_model->getEnterList($criteria);

		////////////////////
		//  필터링 데이터
		////////////////////
		$this->load->helper('form');

		// 진행상태
		$data['status_filter'] = form_dropdown('status', gs2_op_status_list(3), $this->input->get('status'), 'id="status_filter" class="form-control"');

		// 장비 카테고리
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getAllPartCategories();
		$cats = gs2_convert_for_dropdown($cats);
		$cats['0'] = '--- 전체 ---';
		$data['category_filter'] = form_dropdown('cat_id', $cats, $this->input->get('cat_id'), 'id="category_filter" class="form-control"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--- 전체 ---';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $criteria['office'], 'id="office_filter" class="form-control"');

		$this->load->view('work/work_enter_list', $data);
	}

	// 입고요청
	public function request() {
		$data['title'] = "입고 요청 작성";
		$data['current'] = 'page-enter';

		$this->load->library('form_validation');
		$this->load->helper('form');

		$this->load->view('work/work_enter_add_form', $data);
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
				'op_type'	=> GS2_OP_TYPE_ENTER,	// 업무 종류
				'user_id'	=> $this->session->userdata('user_id'),
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

			$this->load->view('work/work_enter_popup_form', $data);

		} else {
			// var_dump($_POST);

			// 100 - 입고업무
			$this->work_model->createEnterOperation(GS2_OP_TYPE_ENTER, $this->input->post());
			alert_colorbox_close('입고 요청 완료', TRUE);
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

		$data['title'] = '입고 > 작업 상세 보기';
		$data['current'] = 'page-enter';
		$data['_config'] = $this->config->item('gs2');

		$work = $this->work_model->get($work_id);
		$data['work'] = $work;

		if($work->status >= "3") {
			$this->load->view('work/work_enter_scan_view', $data);
		} else {
			$this->load->view('work/work_enter_view', $data);
		}
	}


	////////////////
	// ajax 요청 처리 
	////////////////
	public function ajax($action) {
		if(empty($action)){
			echo 'error - $actin 이 비어있음!!!';
		}

		$em = $this->work_model->getEntityManager();

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

			echo 'temp_delete action is done';
		} 
		// 출고 완료
		elseif( $action == 'delivery') {
			$work->setStatus('3');
			$work->setDateModify();

			$this->work_model->_add($work);
			$this->work_model->_commit();

			echo '출고 상태로 변경 하였음!';
		}
		//////////////////////////////
		// 시리얼장비 스캔 결과 저장
		//////////////////////////////
		elseif($action == 'scan_serial_save') {
			$serials = explode(",", $_POST['serials']);
			$items = $work->getItems();

			foreach( $items as $item) {
				if( in_array( $item->serial_number,  $serials)) {
					$item->setScanFlag(TRUE);
					$item->setQtyScan(1);

					$em->persist($item);
				} 
			}

			$em->flush();
			echo '스캔 결과 저장';
		}
		//////////////////////
		// 수량 장비 스캔 결과 저장
		//////////////////////
		elseif($action == 'scan_count_save') {
			$items = $work->getItems();

			foreach($items as $item) {
				$item->setScanFlag(TRUE);
				$item->setQtyScan($_POST['cnt']);

				$em->persist($item);
			}

			$em->persist($work);
			$em->flush();

			echo '수량 : ' . $_POST['cnt'];
		}
		////////////////////
		// 입고 업무 종료
		// ////////////////
		elseif($action == 'complete') {
			$this->load->model('part_m', 'part_model');

			// 업무 메인 
			$work->setStatus('4');
			$work->setDateModify();
			$work->setDateFinish();

			$em->persist($work);

			// 장비 목록
			$complete_count = 0; 
			$items = $work->getItems();

			$idx = 0;

			// 시리얼장비는 입고 수량 만큼 row가 생성되므로
			foreach($items as $item) {
				if ($item->isScan()) { 
					//$item->setCompleteFlag(TRUE);
					$complete_count += $item->qty_scan;

					// 시리얼 관리 장비 등록
					if($item->part_type == '1') {
						$location_string = gs2_encode_location($work->office);

						$data = array(
							'part'		=> $item->part,
							'part_id'	=> $item->part->id,
							'serial_number'		=> $item->serial_number,
							'previous_location'	=> $work->work_location,
							'current_location'	=> $location_string,
							'is_valid'	=> 'Y',
							'is_new'	=> 'Y',
							'qty'		=> 1,
							'date_enter'=> 'now',
							'status'	=> '1',
							'memo'	=> '',
						);

						$new = $this->part_model->addSerialPart($data, FALSE);
						$stock = $work->office->in($item->part, $item->getQtyScan(), 'new');
					} 
					// 수량, 소모품 재고 변경
					else 
					{
						$stock = $work->office->in($item->part, $work->getTotalScanQty(), 'new');
					}				 
				}
			}

			//////////////////
			// 발주 수량 뺴기
			//////////////////
			if($item->part_type == '1') {
				$stock = $item->part->getStock($work->office->id);
			}
			$stock->setQtyS100($stock->qty_s100 - $work->getTotalRequestQty());
			$em->persist($stock);

			/////////////
			// 로그 기록 
			/////////////
			$log_data = array(
				'type' => '1',
				'content' => gs2_op_type($work->type) . ' 작업을 완료 합니다',
				'date_complete' => $this->input->post('date_complete'),
				);
			$this->work_model->addLog($work, $log_data);

			// at last, flush
			$em->flush();
			
			echo  '완료수량 : '. $complete_count . "\n입고 업무를 완료합니다.";

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
