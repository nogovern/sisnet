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

	public function lists() {
		$data['title'] = '장비 상태변경 업무';
		$data['current'] = 'page-change';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getChangeList();

		$this->load->view('work/work_change_list', $data);
	}

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
			->orderBy('w.id', 'DESC');

		// 결과 중 gs2_operation_targets 에 등록 안된것만 필터링
		$data['rows'] = array();
		$rows = $qb->getQuery()->getResult();
		foreach($rows as $row) {
			$find = $em->getRepository('Entity\OperationTarget')->findOneBy(array('target' => $row));
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
			$post_data['date_request'] = '';
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
		}
	}

	public function view($id) {
		$data['title'] = '장비 상태변경 변경 - 등록';
		$data['current'] = 'page-change';

		$op = $this->work_model->get($id);
		if(!$op) {
			alert("요청하신 업무가 존재하지 않습니다.");
		}

		$em = $this->work_model->getEntityManager();
		$targets = $em->getRepository('Entity\OperationTarget')->findBy(array('operation' => $op));

		$data['op']	= $op;
		$data['targets'] = $targets;

		$this->load->view('work/work_change_view', $data);
	}


}