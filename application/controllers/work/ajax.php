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

		// 교체업무 - 확정 시 설치,교체 업무 생성
		if($op->type == '400') {
			$store = gs2_decode_location($op->work_location);
			
			// 설치 업무 생성
			$op_data['op_type'] 		= '205';
			$op_data['office_id'] 		= $op->office->id;
			$op_data['user_id']			= $op->user->id;
			$op_data['store_id'] 		= $store->id;
			$op_data['date_request'] 	= $op->getDateExpect(TRUE);
			$op_data['memo']			= $this->input->post('memo');
			$op_data['status']			= '2';

			$install_op = $this->work_model->createOperation('205', $op_data);
			
			$t_data = array(
				'worker_id'		=> $this->input->post('install_worker_id'),
				'date_expect' 	=> $this->input->post('install_expect_date'),
			);
			$this->work_model->updateOperation($install_op, $t_data, TRUE); 

			// 철수 업무 생성
			$op_data['op_type'] = '305';
			$op_data['date_request'] 	= $op->getDateRequest(TRUE);
			$close_op = $this->work_model->createOperation('305', $op_data);

			$t_data = array(
				'worker_id'		=> $this->input->post('close_worker_id'),
				'date_expect' 	=> $this->input->post('close_expect_date'),
			);

			$this->work_model->updateOperation($close_op, $t_data, TRUE); 

			// 대상 업무로 등록
			$tg1 = $this->work_model->createTargetOperation($op, $install_op);
			$tg2 = $this->work_model->createTargetOperation($op, $close_op);
			
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

		// load model
		$this->load->model('part_m', 'part_model');

		// 에러가 없으면 끝까지 NULL 이다
		$error_msg = NULL;

		$serial_number = $this->input->post('serial_number');	// 시리얼넘버

		// 철수 - 시리얼 장비 일때 처리
		if($op->type >= '300' && $op->type < '400') {
			if($this->input->post('part_type')  == '1') {
				if($serial_number) {

					// 장비목록에 있는 시리얼넘버 인지 검사
					if($it = $this->work_model->checkSerialPartInItem($op, $serial_number)) {
						$error_msg = "등록실패:\n철수 장비 리스트에 등록 된 시리얼넘버 입니다";
					} 
					
					// 등록 된 시리얼장비 라면 사무소,비가용여부,상태 비교
					if(is_null($error_msg)) {
						$sp = $this->part_model->getPartBySerialNumber($serial_number, NULL, TRUE);

						if($sp) {
							if($sp->is_valid == 'Y' || $sp->status != '1') {
								$error_msg = "등록실패:\n" . $sp->part->name . "	 - 사용할 수 없는 시리얼 입니다";
							}
						}
					}
				} else {
					$sp = NULL;
				}
			}
		}

		$serial_part_id = isset($sp) ? $sp : $this->input->post('serial_part_id');

		if(is_null($error_msg)) {
			$post_data = array(
				'id'				=> $this->input->post('id'),		// 작업 ID
				'part_id'			=> $this->input->post('part_id'),
				'serial_number'		=> $serial_number,
				'serial_part_id'	=> $serial_part_id,
				'qty'				=> $this->input->post('qty'),
				'is_new'			=> $this->input->post('is_new'),
			);

			// 철수 시 장비 분실 항목 체크 시 처리
			if(isset($_POST['is_lost']) && $_POST['is_lost'] == 'Y') {
				$post_data['qty_lost'] = $this->input->post('qty');
			}

			// id를 얻기 위해 일단 flush
			$item = $this->work_model->addItem($op, $post_data, TRUE);
		}
		
		if(is_null($error_msg) && !$item) {
			$error_msg = "장비 등록에 실패하였습니다.\n 관리자에게 문의 바랍니다";
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
				$stock = $this->part_model->createStock($stock_arr);
			}

			$qty = intval($_POST['qty']);
			$is_new = ($_POST['is_new'] == 'Y') ? TRUE : FALSE;

			// 입고 업무 시
			if( $op->type == '100') {
				$stock->increase('s100', $qty);					// 발주 수량 update
			} 
			// 설치 업무일 경우
			elseif( $op->type >= '200' && $op->type < '300') {
				// 신품,중고 구별
				if($is_new) {
					$stock->decrease('new', $qty);
				} else {
					$stock->decrease('used', $qty);
				}

				// 설치중 수량 증가
				$stock->increase('s200', $qty);
			}
			// 이동업무
			elseif ($op->type == '700') {
				// 신품,중고 구별
				if($is_new) {
					$stock->decrease('new', $qty);
				} else {
					$stock->decrease('used', $qty);
				}

				// 이동중 수량은 어디에???
				// 비가용 수량으로 넣어야 하나???
				// $stock->increase('s200', $qty);
			} 

			$this->em->persist($stock);
			$this->em->flush();
		}

		// json 결과 객체
		$result = new stdClass;	

		if($error_msg) {
			$result->error = TRUE;
			$result->error_msg = $error_msg;
		} else {
			$result->error = FALSE;
			$result->id = $item->id;			// 새로운 opertaion_parts.id
			$result->error_msg = '';
		}

		echo json_encode($result);
	}

	// 등록 된 장비 삭제
	public function remove_item() {
		$op = $this->em->getReference('Entity\Operation', $_POST['id']);
		$item = $this->em->getReference('Entity\OperationPart', $_POST['item_id']);
			
		// 장비 재고량 수정
		$part = $item->part;
		$stock = $part->getStock($op->office->id);

		// 수량
		$qty = intval($item->qty_request);

		// 설치 	
		if($op->type >= '200' && $op->type < '300') {
			
			$stock->decrease('s200', $qty);

			// 신품,중고 구별
			if($item->isNew()) {
				$stock->increase('new', $qty);
			} else {
				$stock->increase('used', $qty);
			}

		} elseif($op->type == '700') {
			// 신품,중고 구별
			if($item->isNew()) {
				$stock->increase('new', $qty);
			} else {
				$stock->increase('used', $qty);
			}			
		}
		$this->em->persist($stock);

		// 모델로 가는게 맞을까???
		// 시리얼장비 (gs2_part_serial) 정보 복구
		if($item->part_type == '1' && $item->serial_part) {
			$sp = $this->work_model->getReference($item->serial_part->id, 'Entity\SerialPart');
			if($sp) {
				$sp->setValidFlag(TRUE);
				$sp->setStatus('1');

				$this->em->persist($sp);
			}
		}

		$this->work_model->removeItem($item);
		$this->em->flush();			

		echo sprintf('%s 장비를 목록에서 삭제하였습니다', $item->part_name);
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

		if($op->worker->id == $this->input->post("worker_id")) {
			$result['error'] = TRUE;
			$result['error_msg'] = '현재의 담당 직원입니다';

			echo json_encode($result);
			exit;
		}

		// 작업자 변경
		$data = array(
			'worker_id' => $this->input->post('worker_id'),
		);
		$this->work_model->updateOperation($op, $data);

		// 로그 기록
		$log_data = array(
			'type'		=> '1',
			'content'	=> $this->input->post('memo'),
			'event'		=> '작업자 변경'
		);
		$this->work_model->addLog($op, $log_data, TRUE);

		// 결과
		$result['error'] = FALSE;
		$result['error_msg'] = '';

		echo json_encode($result);
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

		if($op->type >= '100' && $op->type < '200') {
			$return_url = base_url() . 'work/enter';
		} else if($op->type >= '200' && $op->type < '300') {
			$return_url = base_url() . 'work/install';
		} else if($op->type >= '300' && $op->type < '400') {
			$return_url = base_url() . 'work/close';
		} else if($op->type >= '400' && $op->type < '500') {
			$return_url = base_url() . 'work/replace';
		} else if($op->type >= '500' && $op->type < '600') {
			$return_url = base_url() . 'work/repair';
		} else if($op->type >= '600' && $op->type < '700') {
			$return_url = base_url() . 'work/destroy';
		} else if($op->type >= '700' && $op->type < '800') {
			$return_url = base_url() . 'work/move';
		} else if($op->type >= '800' && $op->type < '900') {
			$return_url = base_url() . 'work/transfer';
		}

		$response['error'] = false;
		$response['data'] = $return_url;
		
		echo json_encode($response);
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
	public function complete($id = null) {
		$id = ($this->input->post('id')) ? $this->input->post('id') : $id;

		// 완료 상태는 업무에 따라 달라질 수 있음
		$status = '4';	
		$result = $this->work_model->complete($id, $status, $this->input->post('date_complete'));		
		
		echo $result;
	}

	// 승인 
	public function approve() {
		$id = $this->input->post('id');
		$op = $this->work_model->get($id);

		$next_status = intval($op->getStatus()) + 1;

		// 다음 상태로 변경
		$this->work_model->updateOperation($op, array('status' => $next_status));

		$log_data = array(
			'type'		=> '1',
			'content' 	=> '승인 완료',
			'event'		=> '승인'
		);

		$this->work_model->addLog($op, $log_data, TRUE);
		echo 'success';
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

		// 요청 단계가 아니면 더 이상 진행 못 함
		if($op->status != '1') {
			echo 'fail';
			exit;
		}

		// 입고 업무는 재고의 발주 수량을 빼야한
		if($op->type == '100') {
			foreach($op->getItems() as $item) {
				$part = $item->part;
				$stock = $part->getStock($op->office->id);

				$stock->decrease('s100', intval($item->getQtyRequest()));
			}
		}

		// 교체 업무 - 대상(target) 업무 먼저 삭제
		if($op->type == '400' || $op->type == '900') {
			foreach($op->targets as $top) {
				$this->work_model->removeTarget($top->target);
			}
		}

		// 상태 변경  - 대상 업무 삭제 하고 item flag 도 원복 해야 함
		// ( 할필요 없나?? )
		// ** 미구현 

		// 업무 삭제
		$this->work_model->removeOperation($op, TRUE);
		echo 'success';
	}

	/**
	 * 개선된 장비등록 함수
	 */
	public function addItem2() {
		$op_id= $this->input->get('id');		// 작업 ID

		$op = $this->work_model->get($op_id);

		// 폐기 업무 장비 등록
		if($op->type >= '600' && $op->type < '700') {
			// 업무 상태가 1 이면 '입력' 상태로 변경
			if($op->status == '1') {
				$this->work_model->updateOperation($op, array('status' => '2'), TRUE);
			}

			$this->load->model('destroy_m');
			$item = $this->destroy_m->addItem($op, $this->input->get());
		}

		// 결과 출력
		if(is_object($item)) {
			$result['error'] = false;
			$result['id'] = $item->id;
		} else {
			$result['error'] = true;
			$result['error_msg'] = $item;		// 에러 시 에러 문구를 리턴한다
		}

		echo json_encode($result);
	}

	public function removeItem2() {

	}

	// 업무 완료
	public function complete2($id) {
		$op = $this->work_model->get($id);

		// 폐기 업무 완료
		if($op->type >= '600' && $op->type < '700') {
			$this->load->model('destroy_m');
			$result = $this->destroy_m->complete($op);
		}

		$result['error'] = false;

		return json_encode($result); 
	}

	/**
	 * iframe 방식의 업무완료 작성 양식
	 * 
	 * @return [type] [description]
	 */
	public function iframe_complete($id) {
		$data['title'] 		= '업무 완료';
		$data['current']	= "page-schedlue";

		$op = $this->work_model->get($id);
		$data['work'] = $op;

		// POST 저장 시
		$data['form_saved'] = 'n'; 

		$this->load->library('form_validation');

		$this->form_validation->set_rules('op_id', '작업 ID', 'required');
		$this->form_validation->set_rules('date_complete', '완료일시', 'required');

		if($this->form_validation->run() === false) {
			$this->load->view("form/iframe_op_complete_form", $data);
		} else {
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
		        	// 업로드 실패 시 
		        	if($this->upload->do_upload() === FALSE) {
		        		$upload_error = TRUE;
		        		break;
		        	} else {
		        		$f_data = $this->upload->data();

		        		//////////////
		        		//  업로드 저장 배열에 추가 정보를 더해 데이터 넘겨준다
		        		//////////////
		        		$f_data['gubun'] = '완료';
		        		$f_data['op_id'] = $op->id;

		        		// gs2_dump($f_data);
		        		$this->file_model->create($f_data);
		        	}
		        }
			}// end of for

			if(isset($upload_error) && $upload_error == TRUE) {
				$data['errors'] = $this->upload->display_errors();
				$this->load->view("form/iframe_op_complete_form", $data);
				// alert("파일 업로드 중 에러가 발생했습니다\nerror: " . $this->upload->display_errors());
			}
			// 파일 업로드 성공 시....
			else {
				// 완료 상태로 변경
				$result = $this->work_model->complete($op->id, '4', $this->input->post('date_complete'));
				
				if(!$result) {
					echo '저장중 에러가 발생했습니다';
				} else {
					$data['form_saved'] = 'y';
					$this->load->view("form/iframe_op_complete_form", $data);
				}
				// alert('철수 요청을 등록하였습니다.', site_url('/work/close'));
			}
		}

	}

}

