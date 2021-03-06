<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	상태변경 컨트롤러
*/
class Change extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists($page = 1) {
		$data['title'] = '장비 상태변경 업무';
		$data['current'] = 'page-change';

		///////////////
		// 검색 조건
		///////////////
		$criteria = array();
		
		// 상태
		if($this->input->get('status')) {
			$criteria['status'] = $this->input->get('status');
		}

		if($this->input->get('off_id') === false) {
			$criteria['office'] = (gs2_user_type() == '1') ? $this->session->userdata('office_id') : 0;
		} else {
			$criteria['office'] = $this->input->get('off_id');
		}

		// pagination 초기화
		$config = $this->work_model->setPaginationConfig('work/change/lists/');

		$data['rows'] = $this->work_model->getOperations(GS2_OP_TYPE_CHANGE, $criteria, GS2_LIST_PER_PAGE, $page);
		// 총 결과수
		$total_rows = $this->work_model->numRows(GS2_OP_TYPE_CHANGE, $criteria);
		$config['total_rows'] = $total_rows;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $total_rows;

		// ===============
		//  필터링 데이터
		// ===============
		$this->load->helper('form');

		// 진행상태
		$status_list = array(
			'0'	=> '-- 전체 --',	
			'1'	=> '생성',	
			'2'	=> '완',	
		);
		$data['status_filter'] = form_dropdown('status', $status_list, $this->input->get('status'), 'id="status_filter" class="form-control"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--전체--';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $criteria['office'], 'id="office_filter" class="form-control"');

		$this->load->view('work/work_change_list', $data);
	}

	//////////
	// 등록
	//////////
	public function register() {
		$data['title'] = '장비 상태변경 변경 - 등록';
		$data['current'] = 'page-change';

		$this->load->helper('form');
		$this->load->library('form_validation');

		// == 테스트 ===
		// 철수 업무 목록 중... 상태가 3 이상인 것만
		$em = $this->work_model->getEntityManager();
		$qb = $em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where('w.type >= 300')
			->andWhere('w.type < 400')
			->andWhere('w.status >= 4')
			->andWhere("w.is_complete = 'N' ")
			->andWhere("w.office = :office_id ")
			->orderBy('w.id', 'DESC');

		$qb->setParameter('office_id', $this->session->userdata('office_id'));

		// 결과 중 gs2_operation_targets 에서 상태변경 이면서 등록 안된것만 필터링
		$data['rows'] = array();
		$rows = $qb->getQuery()->getResult();
		foreach($rows as $row) {
			// 장비가 없는 철수일 경우 pass
			if($row->numItems() == 0) {
				continue;
			}

			$find = $em->getRepository('Entity\OperationTarget')->findOneBy(array('target' => $row, 'gubun' => 'change'));
			if(!$find) {
				$data['rows'][] = $row;
			}
		}

		if(!count($data['rows'])) {
			alert('상태변경 등록 할 철수업무가 없습니다');
		}

		// 규칙 설정
		$this->form_validation->set_rules('op_type', '작업 종류', 'required');

		if( $this->form_validation->run() === FALSE) {
			$this->load->view('work/work_change_request_form', $data);
		} else {
			// gs2_dump($_POST);
			$post_data = array();
			$post_data['op_type'] = $this->input->post('op_type');
			$post_data['office_id'] = $this->session->userdata('office_id');
			$post_data['date_request'] = date("Y-m-d");
			$post_data['memo']	= '';

			// 새로 생성된 변경작업 Entity
			$main_op = $this->work_model->addOperation('900', $post_data);
			
			// 대상 작업 목록
			$ops = $this->input->post('target_ops');
			foreach($ops as $target_id) {
				$target = $em->getReference('Entity\Operation', $target_id);
				$new = new Entity\OperationTarget($main_op, $target);
				$em->persist($new);
				//$this->setOperation
			}
			$em->flush();
			redirect('work/change');
		}
	}

	/////////////
	// 상세보기
	/////////////
	public function view($id) {
		$data['title'] = '장비 상태변경 변경 - 등록';
		$data['current'] = 'page-change';

		$op = $this->work_model->get($id);
		if(!$op) {
			alert("요청하신 업무가 존재하지 않습니다.");
		}

		// 규칙 설정
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('op_id', '작업 ID', 'required');

		if($this->form_validation->run() === FALSE) {
			$data['op']	= $op;
			$this->load->view('work/work_change_view', $data);

		} else {
			// gs2_dump($_POST);
			// $em = $this->work_model->getEntityManager();
			$this->load->model('part_m', 'part_model');
			$this->load->model('waitpart_m', 'waitpart_model');		// 수리,폐기 장비 모델
			
			///////////////////////
			// 현재 작업 상태 변경
			///////////////////////
			$op_data['date_finish'] = 'now';
			$op_data['status'] = '2';
			$op_data['is_complete'] = TRUE;
			
			$this->work_model->updateOperation($op, $op_data);

			////////////////////////////////////////////
			// target 업무의 is_complete 를 'Y' 로 변경한다 //
			////////////////////////////////////////////
			foreach($op->targets as $t) {
				$top = $t->target;			// target operation 의 약자 (대상 업무)
				
				$this->work_model->updateOperation($top, array('is_complete' => TRUE));

				/////////////////////////////////
				// 장비 재고량을 변경
				/////////////////////////////////
				foreach($top->getItems() as $item) {
					// 분실 장비는 skip
					if($item->isLost() === TRUE) {
						continue;
					}

					// 대상 업무의 사무소의 재고
					$stock = $item->part->getStock($top->office->id);

					$sp = NULL;
					if($item->part_type == '1') {
						$sp = $this->part_model->getSerialPart($item->serial_part->id);
					}

					// 상태변경 수량 배열
					$post_arr = $_POST['items'][$item->id];

					$qty_used = intval($post_arr[0]);		// 중고 가용
					$qty_s500 = intval($post_arr[1]);		// 수리대기
					$qty_s600 = intval($post_arr[2]);		// 폐기대기

					// 중고 가용 수량
					if($qty_used > 0) {
						$stock->increase('used', $qty_used);
						
						// 시리얼장비
						if($sp) {
							$sp->setValidFlag(TRUE);
							$sp->setStatus('1');
						}
					}

					// 수리 대기 수량
					if($qty_s500 > 0) {
						$stock->increase('s500', $qty_s500);
						
						if($sp) {
							$sp->setStatus('5');
						}
						
						// 대기장비 목록에 등록
						$wp_data['gubun']		= 'R';
						$wp_data['qty']			= $qty_s500;
						$wp_data['op_id']		= $op->id;
						$wp_data['part_id']		= $item->part->id;
						$wp_data['part_type']	= $item->part_type;
						$wp_data['previous_location'] = $item->getPreviousLocation();

						if($sp) {
							$wp_data['serial_id'] = $sp->id;		// 시리얼넘버는 자동으로 채운다
						}

						$wp = $this->waitpart_model->create($wp_data);
					}

					// 폐기 대기 수량
					if($qty_s600 > 0) {
						$stock->increase('s600', $qty_s600);
						if($sp) {
							$sp->setStatus('6');
						}

						// 대기장비 목록에 등록
						$wp_data['gubun']		= 'D';
						$wp_data['qty']			= $qty_s600;
						$wp_data['op_id']		= $op->id;
						$wp_data['part_id']		= $item->part->id;
						$wp_data['part_type']	= $item->part_type;
						$wp_data['previous_location'] = $item->getPreviousLocation();

						if($sp) {
							$wp_data['serial_id'] = $sp->id;		// 시리얼넘버는 자동으로 채운다
						}

						$wp = $this->waitpart_model->create($wp_data);
					}

					if($sp) {
						$this->work_model->_add($sp);
					}

					// 상태변경 수량 배열을 serialize 해서 extra 저장
					$item->setExtra(serialize($post_arr));
					$this->work_model->_add($item);

					// 점검전 수량에서 빼기
					$stock->decrease('s900', intval($item->qty_request));
					$this->work_model->_add($stock);
					
					// gs2_dump($item->extra);
					// gs2_dump($stock->qty_used);

				}// end of item loop
			}

			///////////////
			// log 기록 //
			///////////////
			$log_data = array(
				'type'		=> '1',
				'content'	=> '상태변경 완료',
			);
			$this->work_model->addLog($op, $log_data, TRUE);

			alert('상태변경을 완료하였습니다', site_url('work/change'));
		}

	}

}

