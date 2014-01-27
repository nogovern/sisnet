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
		$data['current'] = 'page-changer';

		$data['status'] = '';
		$data['rows'] = $this->work_model->getChangeList();
		
		$this->load->view('work/work_change_list', $data);
	}

	public function register() {
		$data['title'] = '장비 상태변경 변경 - 등록';
		$data['current'] = 'page-changer';

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
			->orderBy('w.id', 'DESC');

		$rows = $qb->getQuery()->getResult();
		$data['rows'] = $rows;

		if( $this->form_validation->run() === FALSE) {
			$this->load->view('work/work_change_request_form', $data);
		} else {
			gs2_dump($_POST);
		}
		
	}

	public function view($id) {
		$data['title'] = '장비 상태변경 변경 - 등록';
		$data['current'] = 'page-changer';

		$op = $this->work_model->get($id);
		if(!$op) {
			alert("요청하신 업무가 존재하지 않습니다.");
		}

		$em = $this->work_model->getEntityManager();
		$sub_ops = $em->getRepository('Entity\OperaionExtra')->findBy(array('operation_id' => $id));

		$data['op']	= $op;
		$data['sub_ops'] = $sub_ops;

		$this->load->view('work/work_change_view', $data);
	}


}