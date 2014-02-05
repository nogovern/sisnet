<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	work -> ajax 공통 컨트롤러
*/
class Ajax extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
		$this->em = $this->work_model->getEntityManager();
	}

	public function index() {
		echo '워크 ajax 메인';
	}

	// 요청 확정
	public function accept_request() {
		$id = $this->input->post('id');
		$op = $this->work_model->get($id);

		// 공통
		$post_data = array(
			'status'		=> '2'
		);

		// 교체업무
		if($op->type == '400') {
			$post_data['date_expect'] = $this->input->post('close_expect_date');	// 교체 철수 요청일
			$post_data['date_work'] = $this->input->post('install_expect_date');	// 교체 설치 요청일

			foreach($op->targets as $top) {
				// 설치 확정
				if($top->target->type == '205') {
					$t_data = array(
						'worker_id'		=> $this->input->post('install_worker_id'),
						'date_expect' 	=> $this->input->post('install_expect_date'),
						'status'		=> '2'
					);
					$this->work_model->updateOperation($top->target, $t_data); 
				} 
				// 철수 확정
				else {
					$t_data = array(
						'worker_id'		=> $this->input->post('close_worker_id'),
						'date_expect' 	=> $this->input->post('close_expect_date'),
						'status'		=> '2'
					);
					$this->work_model->updateOperation($top->target, $t_data); 
				}
			}
		} else {
			// 작업 예정일
			if(!$this->input->post('date_expect')) {
				$post_data['date_expect'] = $this->input->post('date_work');
			} else {
				$post_data['date_expect'] = $this->input->post('date_expect');
			}

			// 입고가 아닌 경우
			if($op->type >= '200') {
				$post_data['worker_id']	= $this->input->post('worker_id');
			}
		}

		////////////////////
		// 업무 master 변경 
		////////////////////
		$this->work_model->updateOperation($op, $post_data);
			
		$log_data = array(
			'content'	=> $this->input->post('memo'),
			'type'		=> '1',
			'event'		=> '확정'
		);
		// 로그 기록
		$this->work_model->addLog($op, $log_data, TRUE);
		
		echo 'success';
	}

	////////////
	// 장비 등록 - 이 시점에서는 실 재고에 반영 안됨
	////////////
	public function add_item($op_id=0) {
		$id = $this->input->post('id');		// 작업 ID
		if(!$id) {
			log_message('error', __METHOD__ . ' 작업 번호가 없습니다.');
			return FALSE;
		}
		
		$op = $this->em->getReference('Entity\Operation', $id);
		if(!$op) {
			die( __CLASS__ . __METHOD__ . " 작업이 존재하지 않습니다");
		}

		$post_data = array(
			'id'				=> $this->input->post('id'),		// 작업 ID
			'part_id'			=> $this->input->post('part_id'),
			'serial_number'		=> $this->input->post('serial_number'),
			'serial_part_id'	=> $this->input->post('serial_part_id'),
			'qty'				=> $this->input->post('qty'),
			'is_new'			=> $this->input->post('is_new'),
		);

		// 철수 시 장비 분실 항목 체크 시 처리
		if(isset($_POST['is_lost']) && $_POST['is_lost'] == 'Y') {
			$post_data['qty_lost'] = $this->input->post('qty');
		}

		// id를 얻기 위해 일단 flush
		$item = $this->work_model->addItem($op, $post_data, TRUE);
		
		// json 결과 객체
		$result = new stdClass;	

		if(!$item) {
			$result->result = 'failure';
		} else {
			//////////////////////////
			// :: 모델쪽으로 이동해야함 :: //
			//////////////////////////

			$part = $this->em->getReference('Entity\Part', $_POST['part_id']);
			
			// 장비 재고 가져오기 - 없으면 생성
			$stock = $part->getStock($op->office->id);
			if(!$stock) {
				$stock_arr = array(
					'part'		=> $part,
					'office'	=> $op->office,
				);
				$this->load->model('part_m', 'part_model');
				$stock = $this->part_model->createStock($stock_arr);
			}

			$qty = intval($_POST['qty']);
			$is_new = ($_POST['is_new'] == 'Y') ? TRUE : FALSE;

			// 입고 업무 시
			if( $op->type == '100') {
				$stock->setQtyS100($stock->qty_s100 + $qty);	// 발주 수량 update
			} 
			// 설치 업무일 경우
			elseif( $op->type >= '200' && $op->type < '300') {
				// 신품,중고 구별
				if($is_new) {
					$stock->setQtyNew($stock->qty_new - $qty);
				} else {
					$stock->setQtyUsed($stock->qty_used - $qty);
				}

				$stock->setQtyS200($stock->qty_s200 + $qty);
			}

			$this->em->persist($stock);
			$this->em->flush();

			$result->id = $item->id;			// 새로운 opertaion_parts.id
			$result->result = 'success';
		}

		echo json_encode($result);
	}

	// 등록 된 장비 삭제
	public function remove_item() {
		$op = $this->em->getReference('Entity\Operation', $_POST['id']);
		$item = $this->em->getReference('Entity\OperationPart', $_POST['item_id']);
			
		$this->work_model->removeItem($item);

		if($item->operation->type >= '200' && $item->operation->type < '300')
		{
			// 장비 재고량 수정
			$part = $this->em->getReference('Entity\Part', $item->part->id);
			$stock = $part->getStock($op->office->id);

			$qty = $item->qty_request;
			$stock->setQtyS200($stock->qty_s200 - $qty);

			// 신품,중고 구별
			if($item->isNew()) {
				$stock->setQtyNew($stock->qty_new + $qty);
			} else {
				$stock->setQtyUsed($stock->qty_used + $qty);
			}

			$this->em->persist($stock);
		}
		
		$this->em->flush();			

		echo '[Install] remove_item action is done';
	}

	// 작업자 메모
	public function write_memo() {
		$id = $this->input->post('id');
		$op = $this->work_model->get($id);
		
		$post = array(
			'content'	=> $this->input->post('memo'),
			'type'		=> '2',
		);

		$this->work_model->addLog($op, $post, TRUE);

		echo '메모를 저장하였습니다';
	}

	// 방문자 변경
	public function change_worker() {
		$id = $this->input->post('id');
		$op = $this->work_model->get($id);

		// 작업자 변경
		$data = array(
			'worker_id' => $this->input->post('worker_id'),
		);
		$this->work_model->updateOperation($op, $data);

		// 로그 기록
		$log_data = array(
			'type'		=> '1',
			'content'	=> '방문자 변경 되었음',
			'event'		=> '작업자 변경'
		);
		$this->work_model->addLog($op, $log_data, TRUE);

		echo '작업자를 변경하였습니다';
	}

	// 담당 사무소 변경
	public function change_office() {
		$id = $this->input->post('id');
		$op = $this->work_model->get($id);

		// 작업자 변경
		$data = array(
			'office_id' => $this->input->post('office_id'),
		);
		$this->work_model->updateOperation($op, $data);

		// 로그 기록
		$log_data = array(
			'type'		=> '1',
			'content'	=> '담당 사무소 변경 완료',
			'event'		=> '사무소 변경'
		);
		$this->work_model->addLog($op, $log_data, TRUE);

		echo '담당 사무소를 변경하였습니다';
	}

	// 점포 완료
	public function store_complete() {
		$id = $this->input->post('id');
		$op = $this->work_model->get($id);
		
		// 업무 main 변경
		$op_data = array(
			'status'		=> '3',
			'date_work'		=> $this->input->post('date_complete'),		// 작업일
		);
		$this->work_model->updateOperation($op, $op_data);
		
		// 업무 log 생성
		$log_data = array(
			'type' 			=> '1',
			'content' 		=> $this->input->post('memo'),
			'event'			=> '점포완료'
		);
		$this->work_model->addLog($op, $log_data, TRUE);

		echo '점포완료 로 변경하였음';
	}

	// 작업 완료
	public function complete($operation_id=NULL) {
		$id = $this->input->post('id');
		// 테스트용
		if(!$id) {
			$id = $operation_id;
		}

		$op = $this->work_model->get($id);
		// 추가 모델 로딩
		$this->load->model('part_m', 'part_model');

		// 업무 메인 변경
		$op_data['status'] = '4';
		$op_data['date_finish'] = $this->input->post('date_complete');
		$this->work_model->updateOperation($op, $op_data);

		// 업무 장비 정보 변경

		if( $op->type >= '200' && $op->type < '300') {
			// 장비 출고 후 재고 반영
			$this->work_model->deliveryItem($op);
		} 
		// 철수 후 장비 상태는 점검 전
		elseif ( $op->type >= '300' && $op->type < '400') {
			$items = $op->getItems();

			foreach($items as $item) {
				if($item->part_type == '1') {
					// 기존 시리얼넘버가 존재하면 기존 정보 update
					$sp = $this->part_model->getPartBySerialNumber($item->serial_number);

					// 없는 시리얼넘버이면 생성
					if(!$sp) 
					{
						$sp_data['part_id'] = $item->part->id;
						$sp_data['previous_location'] = $op->work_location;
						$sp_data['current_location'] = gs2_encode_location($op->office);
						$sp_data['date_enter']	= $op->getDateFinish();				// 최초 설치일
						$sp_data['qty'] = 1;
						$sp_data['serial_number'] = $item->serial_number;
						$sp_data['is_new'] = $item->is_new;
						$sp_data['status'] = '1';
						$sp_data['is_valid'] = 'N';									// 가용 여부


						$sp = $this->part_model->addSerialPart($sp_data, FALSE);
					} 

					else {
						//////////////////////////////////////////////
						// part_m 안에 updateSerialPart 로 구현하자! 
						//////////////////////////////////////////////
						$sp->setPreviousLocation($op->work_location);
						$sp->setDateEnter($op->getDateFinish());
						$sp->setNewFlag(FALSE);
						$sp->setValidFlag(FALSE);
						$sp->setCurrentLocation(gs2_encode_location($op->office));

					}

					///////////////////////
					// 분실 장비일 경우 처리 
					///////////////////////
					if($item->qty_lost > 0) {
						$sp->setStatus('L');
						$sp->setMemo('분실장비');
					}

					$this->part_model->_add($sp);

				}

				// 재고수량 변경
				$stock = $item->part->getStock($op->office->id);
				$stock->increase('s900', $item->qty_request);	// 점검 전 수량에 추가
				$this->work_model->_add($stock);
			}
		}

		// 점포 상태 변경 (설치 - 정상, 철수 - 폐점, 휴점, 리뉴얼, 교체)
		

		// 업무 log 생성
		$log_data = array(
			'type' => '1',
			'content' => gs2_op_type($op->type) . ' 작업완료 합니다',
			'event'			=> '완료'
		);
		$this->work_model->addLog($op, $log_data, TRUE);
		
		echo 'operation complete is done successfully!';
	}

	// 승인 
	public function approve() {
		$id = $this->input->post('id');
		$op = $this->work_model->get($id);

		$next_status = intval($op->getStatus()) + 1;

		// 다음 상태로 변경
		$this->work_model->updateOpration($op, array('status' => $next_status));

		$log_data = array(
			'type'		=> '1',
			'content' => '승인 완료',
			'event'			=> '완료'
		);
	}

	// 입고 - 아이템 정보 갱신
	public function update_item($action=NULL) {

		$error = FALSE;
		$error_msg = '';

		$op = $this->em->getReference('Entity\Operation', $_POST['id']);
		// $item = $this->em->getReference('Entity\OperationPart', $_POST['item_id']);
		$part_type = $op->getItem()->part_type;

		if($action == 'register') {
			// 시리얼 장비
			if($part_type == '1') {
				// 시리얼 중복 검사 (1차)
				foreach($op->getItems() as $item) {
					if($item->serial_number == $_POST['serial_number']) {
						$error_msg = '에러! 시리얼넘버 중복 발생';
						$error = TRUE;
					}
				}
				
				// 시리얼 중복 검사 (2차)
				if(!$error) {
					$s_part = $this->em->getRepository('Entity\SerialPart')->findBy(array('serial_number' => $_POST['serial_number']));
					if($s_part) {
						$error_msg = '에러! DB에 이미 등록된 장비가 있습니다';
						$error = TRUE;
					}
				}

				if(!$error) {
					// 시리얼 넘버 등록 안된 item 1만 update
					foreach($op->getItems() as $item) {
						if($item->qty_complete == 0) {
							$item->setQtyComplete(1);
							$item->setSerialNumber($_POST['serial_number']);

							break;
						} 
					}
				}
			} 
			// 수량 장비
			else {
				$item = $op->getItem();
				$item->setQtyComplete($_POST['qty']);				
			}

		}
		// 입고 등록 장비 정보 초기화
		elseif($action == 'reset') {
			$item = $this->em->getReference('Entity\OperationPart', $_POST['item_id']);
			$item->setQtyComplete(0);
			$item->setSerialNumber('');
		}

		// json 결과 객체
		$result = new stdClass;

		// 에러 없으면 ...
		if(!$error) {
			$this->em->flush();

			// 화면 갱신을 위해 id를 리턴
			$result->item_id = $item->id;
		}

		$result->error = $error;
		$result->error_msg = $error_msg;

		echo json_encode($result);
	}// end of update_item()


	////////////////
	// 스캔 정보 초기화 //
	////////////////
	public function scan_reset() {
		$id = $this->input->post('id');

		$op = $this->em->getReference('Entity\Operation', $id);
		$items = $op->getItems();

		foreach($items as $item) {
			$item->setScanFlag(FALSE);
			$item->setQtyScan(0);

			$this->em->persist($item);
		}

		$this->em->flush();

		echo '초기화 완료';
	}
	

	//////////////////////
	// 업무 메모 리스트 
	//////////////////////
	public function loadUserMemo($op_id) {
		$op = $this->work_model->get($op_id);
		$data['logs'] = $this->work_model->getLogs($op);

		$this->load->view('common/work_memo_list', $data);
	}

	///////////////////
	// 업무 log 리스트
	///////////////////
	public function loadOperationLog($op_id) {
		$op = $this->work_model->get($op_id);
		$data['logs'] = $this->work_model->getLogs($op);
		
	}
	
	///////////////////
	// 업무 삭제
	///////////////////
	public function remove_operation() {
		$id = $this->input->post('id');
		if(!$id) {
			log_message('error', '업무 ID가 없습니다');
			return FALSE;
		}


		$op = $this->em->getRepository('Entity\Operation')->find($id);
		if(!$op) {
			log_message('error', $id . ' 의 업무가 존재하지 않습니다');
			echo 'no exist';
			exit;
		}

		if($op->status == 1) {
			// 입고 업무는 재고의 발주 수량을 빼야한
			if($op->type == '100') {
				foreach($op->getItems() as $item) {
					$part = $item->part;
					$stock = $part->getStock($op->office->id);

					$stock->decrease('s100', intval($item->getQtyRequest()));
				}
			}

			// 업무 삭제
			$this->work_model->removeOperation($op, TRUE);
			echo 'success';
		} else {
			echo 'fail';
		}
	}
}



