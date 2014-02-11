<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	공통 Ajax 응답 컨트롤러
*/
class Ajax extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
	}

	public function index() {
		show_404();
	}

	public function request_enter_form($part_id) {
		if(empty($part_id)){
			trigger_error("장비 ID는 필수입니다.");
			exit;
		}

		$this->load->library('form_validation');
		$this->load->helper('form');

		$data['title'] = '장비 입고 요청서';

		$this->load->view('popup_request_enter_form', $data);
	}

	/**
	 * 출고용 장비 모델 리스트 반환
	 * - 담당 사무소의 재고량 도 함께 불러온다 
	 * 
	 * @param  integer $category_id 	
	 * @return string              		select/option html 엘리먼트
	 */
	public function get_model_list_for_delivery($category_id) {
		// 주의 
		// isset($_POST) 하면 항상 TRUE 를 리턴한다.
		if(!empty($_POST['category_id'])) {
			$category_id = $this->input->post('category_id');
		}

		$this->load->model('part_m');

		$em = $this->part_m->getEntityManager();
		$category = $em->getReference("Entity\Category", $category_id);
		
		// $qb = $em->createQueryBuilder();
		// $qb->select("p")->from("Entity\Part", "p");
		// $qb->where("p.category = :cat");
		// $qb->andWhere("p.type != '1'");
		// $qb->setParameter('cat', $category_id);
		// $parts = $qb->getQuery()->getResult();

		$parts = $em->getRepository('Entity\Part')->findBy(array('category' => $category));


		// 특정 사무소의 재고 수량을 얻을 경우
		$office_id  = (isset($_POST['office_id']) && !empty($_POST['office_id'])) ? $_POST['office_id'] : NULL;

		header('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
		$output = '';
		if(count($parts)){
			$output .= '<option value="0">--선택하세요--</option>';
			foreach($parts as $p) {
				// 재고 수량 없거나 시리얼장비일 경우 선택 못함
				$disabled = ( $p->type == '1' || ($p->getNewTotal($office_id) == 0 && $p->getUsedTotal($office_id) == 0) ) ? 'disabled' : '';
				$tpl =  '<option value="%d" %s>%s (%d/%d)</option>';
				$output .= sprintf($tpl, $p->id, $disabled, $p->name, $p->getNewTotal($office_id), $p->getUsedTotal($office_id));
			}

			echo $output;
		} else {
			echo '1000';
		}
	}

	/**
	 * 입고용 장비 모델 목록
	 * 
	 * @param  integer $category_id 
	 * @return string              	select/option html 엘리먼트
	 */
	public function get_model_list_for_warehousing($category_id) {
		if(!$category_id) {
			echo 'error - 카테고리 id 가 없음';
			exit;
		}

		$this->load->model('part_m');

		$em = $this->part_m->getEntityManager();
		
		// $category = $em->getReference("Entity\Category", $category_id);
		// $parts = $em->getRepository('Entity\Part')->findBy(array('category' => $category));
		
		$qb = $em->createQueryBuilder();
		$qb->select('p')
			->from('\Entity\Part', 'p')
			->where("p.type != '3' ")				// 소모품 제외
			->andWhere("p.category = :cat")
			->orderBy('p.name', 'ASC')
			->setParameter('cat', $category_id);
		
		$parts = $qb->getQuery()->getResult();

		header('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
		$output = '';
		if(count($parts)){
			$output .= '<option value="0">--선택하세요--</option>';
			foreach($parts as $p) {
				$tpl =  '<option value="%d">%s</option>';
				$output .= sprintf($tpl, $p->id, $p->name);
			}	

			echo $output;
		} else {
			echo 'none';
		}
	}

	// 직전위치로 장비 검색
	public function search_part_by_previous_location($query) {

	}

	// 시리얼로  장비 검색
	public function search_part_by_serial($query) {
		$query = (empty($query)) ? $_POST['input_text'] : $query;
		if(empty($query)) {
			log_message('error', __METHOD__ . ' 시리얼넘버 가 비어있음!');
		}

		$sn = urldecode($query);

		$this->load->model('part_m', 'part_model');
		$result = $this->part_model->getPartBySerialNumber($sn);

		echo ($result) ? 'false' : 'true';
	}

	// 시리얼장비 로 장비 정보 json 형식으로 반환
	// 	- 출고 시 시리얼장비인 경우 검색
	public function get_serial_by_sn($sn='') {
		$error_msg= '';

		$sn = (isset($_POST['serial_number'])) ? $this->input->post('serial_number') : $sn;
		if(empty($sn)) {
			$error_msg = '시리얼넘버가 없습니다';
			log_message('error', $error_msg);
		}

		$sn = urldecode($sn);

		$this->load->model('part_m', 'part_model');
		// 시리얼장비
		$sp = $this->part_model->getPartBySerialNumber($sn, $this->input->post('office_id'), TRUE);

		if(!$sp) {
			$error_msg = '입력한 시리얼넘버 의 장비가 없습니다.';
			$error_msg .= "\n없는 장비 이거나 다른 사무소의 장비 일 수 있습니다";
		} else {
			// 해당 시리얼 장비가 비가용 상태 일 경우
			if($sp->isValid() === FALSE) {
				$error_msg =  "해당 장비가 비가용 상태이므로\n등록할 수 없습니다";
			} else {
				$prev_location = gs2_decode_location($sp->previous_location);
				if($prev_location) {
					$prev_location = $prev_location->name;
				}

				$info = array(
					'spart_id'		=> $sp->id,			// gs2_part_serial.id
					'category_id'	=> $sp->part->category->id,
					'part_id'		=> $sp->part->id,
					'serial_number'	=> $sp->serial_number,
					'prev_location' => $prev_location,
					'is_new'		=> $sp->is_new,
				);
			}
		}

		$result = new stdClass;
		if(!empty($error_msg)) {
			$result->error = TRUE;
		} else {
			$result->error = FALSE;
			$result->info = $info;
		}

		$result->error_msg = $error_msg;
		echo json_encode($result);
	}

	// 시리얼장비 정보를 json 으로 형식으로 반환
	public function get_serial($sp_id) {
		$error_msg = '';

		if(!$sp_id) {
			$error_msg = '시리얼장비 ID 는 필수 인자 입니다';
		}

		$this->load->model('part_m', 'part_model');
		$sp = $this->part_model->getSerialPart($this->input->post('sp_id'));

		if(!$sp) {
			$error_msg = '입력한 시리얼넘버 의 장비가 없습니다.';
			$error_msg .= "\n없는 장비 이거나 다른 사무소의 장비 일 수 있습니다";
		} else {
			// 해당 시리얼 장비가 비가용 상태 일 경우
			if($sp->isValid() === FALSE) {
				$error_msg =  "해당 장비가 비가용 상태이므로\n등록할 수 없습니다";
			} else {
				$prev_location = gs2_decode_location($sp->previous_location);
				if($prev_location) {
					$prev_location = $prev_location->name;
				}

				$info = array(
					'spart_id'		=> $sp->id,			// gs2_part_serial.id
					'category_id'	=> $sp->part->category->id,
					'part_id'		=> $sp->part->id,
					'serial_number'	=> $sp->serial_number,
					'prev_location' => $prev_location,
					'is_new'		=> $sp->is_new,
				);
			}
		}

		// 결과 class 생성
		$result = new stdClass;
		if(!empty($error_msg)) {
			$result->error = TRUE;
		} else {
			$result->error = FALSE;
			$result->info = $info;
		}

		$result->error_msg = $error_msg;
		echo json_encode($result);
	}

	// 등록된 사용자명인지 검사
	public function is_exist_username() {
		$this->load->model('user_m', 'user_model');

		$user = $this->user_model->getByUsername($this->input->post('username'));

		// jquery validate remote 처리시 
		// false 를 출력해야 error 로 판단
		// username 검색 결과 가 있을 시 false 를 출력해야 함
		echo ($user) ? 'false' : 'true';
	}

	// 등록된 사무소명 이지 검사 
	public function is_exist_office_name($name = '') {

		// uri 에 검색어가 한글일 경우 urldecode 해줘야 하는군...
		$office_name = ($name) ? urldecode($name) : $_POST['name'];

		$this->load->model('office_m', 'office_model');
		$result = $this->office_model->getByName($office_name);

		header('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
		echo (!$result) ? 'true' : 'false';
	}

	// 등록된 점포명인지 검사
	public function is_exist_store_name($name='') {
		// uri 에 검색어가 한글일 경우 urldecode 해줘야 하는군...
		$name = !empty($name) ? urldecode($name) : $this->input->post('name');

		$this->load->model('store_m', 'store_model');
		$result = $this->store_model->getByName($name);

		echo (!$result) ? 'true' : 'false';
	}

	// 등록된 점포 코드 인지 검사
	public function is_exist_store_code($query='') {
		// uri 에 검색어가 한글일 경우 urldecode 해줘야 하는군...
		$code = !empty($query) ? urldecode($query) : $this->input->post('code');

		$this->load->model('store_m', 'store_model');
		$result = $this->store_model->getByCode($code);

		echo (!$result) ? 'true' : 'false';
	}

	////////////
	// 점포 정보
	////////////
	public function store_info($store_id) {
		$store_id = isset($_POST['sotre_id']) ? $this->input->post('store_id') : $store_id;

		$this->load->model('store_m', 'store_model');
		$em = $this->store_model->getEntityManager();

		$store = $this->store_model->get($store_id);
		
		// postbox 타입
		$postbox = array('0' => "미설치", '일반설치', 'MMK설치');		

		$result = new stdClass;

		// extract from Entity
		$cols = $em->getClassMetadata('Entity\Store')->getColumnNames();
		foreach ($cols as  $value) {
			if( $value == 'date_register') {
				$result->$value = $store->getDateRegister();
			} elseif( $value == 'join_type') {
				$result->$value = gs2_get_store_join_type($store->$value);
			} elseif( $value == 'has_postbox') {
				if(is_null($store->$value)){ 
					$val = '';
				} else {
					$val = $postbox[$store->$value];
				}
				$result->$value = $val;
			} else {
				$result->$value = ($store->$value) ? $store->$value : '';
			}
		}
		echo json_encode($result);
	}


}