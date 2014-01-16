<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	work -> ajax 공통 컨트롤러
*/
class Ajax extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		// operation.id 가 post 로 넘어오는지 검사!
		$id = $this->input->post('id');
		if(!$id) {
			die('잘못된 접근입니다.');
		}

		$this->load->model('work_m', 'work_model');
		$this->em = $this->work_model->getEntityManager();
	}

	public function index() {
		echo '워크 메인';
	}

	// 요청 확정
	public function accept_request() {
		$id = $this->input->post('id');

		$post = array(
			'office_id'	=> $this->input->post('office_id'),
			'worker_id'	=> $this->input->post('worker_id'),
			'date_work'	=> $this->input->post('date_work'),
			'memo'		=> $this->input->post('memo')
			);
		
		$op = $this->work_model->acceptRequest($id, $post);
		echo 'success';
	}

	////////////
	// 장비 등록 - 이 시점에서는 실 재고에 반영 안됨
	////////////
	public function add_item() {
		$id = $this->input->post('id');		// 작업 ID

		$op = $this->em->getReference('Entity\Operation', $_POST['id']);
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
			// 장비 재고량 변경
			$stock = $part->getStock($op->office->id);
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
		
		$post = array(
			'user_id'	=> $this->session->userdata('user_id'),			// 로그인 한 유저
			'content'	=> $this->input->post('memo'),
			'type'		=> '2',
		);

		$this->work_model->addLog($id, $post, TRUE);

		echo '메모를 저장하였습니다';
	}

	// 점포 완료
	public function store_complete() {
		$id = $this->input->post('id');
		
		// 업무 log 생성
		$log_data = array(
			'user_id'		=> $this->session->userdata('user_id'),
			'content' 		=> $this->input->post('memo'),
			'date_complete' => $this->input->post('date_complete'),
			'type' 			=> '1',
			'next_status' 	=> '3',
		);
		$this->work_model->addLog($id, $log_data);

		$op_data = array(
			'status'		=> '3',
			'date_work'		=> $this->input->post('date_complete'),
		);

		// 3번째 인자를 TRUE 로 하여 flush 실행
		$this->work_model->updateOperation($id, $op_data, TRUE);

		echo '점포 작업 완료 로 변경하였음';
	}

	// 완료
	public function complete() {

	}

	// 승인 
	public function approve() {

	}

	// 입고 - 아이템 정보 갱신
	public function update_item($action=NULL) {
		$item = $this->em->getReference('Entity\OperationPart', $this->input->post('item_id'));

		if($action == 'register') {

		}

		elseif($action == 'remove') {

		}
	}
	
}


