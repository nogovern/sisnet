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

		$post_data = array(
			'date_expect'	=> $this->input->post('date_work'),	// 작업 예정일
			'status'		=> '2'
		);

		// 입고가 아닌 경우
		if($op->type >= '200') {
			//$post_data['office_id']	= $this->input->post('office_id');
			$post_data['worker_id']	= $this->input->post('worker_id');
		}

		// 업무 master 변경
		$this->work_model->updateOperation($op, $post_data);
			
		$log_data = array(
			'content'	=> $this->input->post('memo'),		// for 테스트
			'type'		=> '1',
			'event'		=> '요청확정'
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
		if(isset($_POST['qty_lost']) && $_POST['qty_lost'] > 0) {
			$post_data['qty_lost'] = $this->input->post('qty_lost');
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
		);
		$this->work_model->addLog($op, $log_data, TRUE);

		echo '작업자 변경 완료';
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
			'content' 		=> $this->input->post('memo') . '\n\n -- 점포 완료 입력됨 --',
			'date_complete' => $this->input->post('date_complete'),
			'type' 			=> '1',
			'next_status' 	=> '3',
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

					// 없는 시리얼넘버이면 생성
					$post['part_id'] = $item->part->id;
					$post['is_valid'] = 'N';									// 가용 여부
					$post['current_location'] = $op->office;
					$post['previous_location'] = $op->work_location;
					$post['date_install']	= $op->getDateFinish();				// 최초 설치일
					$post['qty'] = 1;
					$post['serial_number'] = $item->serial_number;
					$post['is_new'] = $item->is_new;

					$this->part_model->addSerialPart($post);

					//var_dump($post);
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
			'content' => gs2_op_type($op->type) . ' 작업을 작업완료 합니다',
			'date_complete' => $this->input->post('date_complete'),
			'type' => '1',
			'next_status' => '4',
			);
		$this->work_model->addLog($op, $log_data, FALSE);
		
		echo 'operation complete is done successfully!';
	}

	// 승인 
	public function approve() {
		$id = $this->input->post('id');
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
	
}


