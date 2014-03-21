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

		$this->load->model('part_m', 'part_model');
		$parts = $this->part_model->getModels($category_id);

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

		// 소모품 제외한 모델 목록
		$this->load->model('part_m');
		$parts = $this->part_m->getModelsExceptAccessory($category_id);		
		
		// 결과 출력
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

	/**
	 * 장비 스캔용 모델 리스트
	 * 
	 * @param  integer $category_id 
	 * @return string              	select/option html 엘리먼트
	 */
	public function get_models_for_scan($category_id) {
		if(!$category_id) {
			echo 'error - 카테고리 id 가 없음';
			exit;
		}

		$this->load->model('part_m');
		$parts = $this->part_m->getModels($category_id);

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

	/**
	 * 필터용 장비 모델 리스트
	 * 
	 * @param  integer $category_id 
	 * @return string              	select/option html 엘리먼트
	 */
	public function get_models_for_filter($category_id) {
		if(empty($category_id)) {
			$parts = array();
		} else {
			$this->load->model('part_m');
			$parts = $this->part_m->getModels($category_id);
		}

		header('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
		$output = '';
		$output .= '<option value="0">--- 전체 ---</option>';

		foreach($parts as $p) {
			$tpl =  '<option value="%d">%s</option>';
			$output .= sprintf($tpl, $p->id, $p->name);
		}	

		echo $output;
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
				$result->$value = gs2_store_join_type($store->$value);
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

	// 업무 카테고리로 서브 카테고리 목록 반환
	// 현재는 일정에서만 쓰임
	public function get_operation_type($op_category) {
		$op_sub_category = array(
			'0' => array(
				'0'	=> '전체'
			),
			'100' => array(
				'0'	=> '전체'
			),
			'200' => array(
				'0'	=> '전체',
				'201'	=> '신규',
				'202'	=> '서비스',
				'203'	=> '휴점C',
				'204'	=> '휴점S',
				'205'	=> '교체',
				'206'	=> '리뉴얼',
			),
			'300' => array(
				'0'	=> '전체',
				'301'	=> '폐점',
				'302'	=> '서비스',
				'303'	=> '휴점C',
				'304'	=> '휴점S',
				'305'	=> '교체',
				'306'	=> '리뉴얼',
			),
			'400' => array(
				'0'	=> '전체',
			),
			'500' => array(
				'0'	=> '전체',
			),
			'600' => array(
				'0'	=> '전체',
				'601'	=> '승인',
				'602'	=> '출고',
			),
			'700' => array(
				'0'	=> '전체',
			),
			'800' => array(
				'0'	=> '전체',
			),
			'900' => array(
				'0'	=> '전체',
			),
		);

		$result = $op_sub_category[$op_category];
		echo json_encode($result);

	}

	// 대기장비 조회 
	public function search_waitpart($q) {
		$this->load->model("waitpart_m", "waitpart_model");

		$criteria['serial_number'] = urldecode($q);
		$criteria['gubun'] = $this->input->get('gubun');
		$criteria['office']	 = $this->input->get('office_id');
		$criteria['qty'] = 0;
		// $criteria['status'] = '1';

		$result = $this->waitpart_model->search($criteria);

		// 없을 경우
		$response = new stdClass;
		if(!$result) {
			$response->error = true;
			$response->error_msg = "장비를 찾을 수 없습니다";
		} else {
			$response->error = false;

			$wpart = $result[0];			// 대기 장비 정보
			$sp = $result[0]->serial_part;

			$prev_location = gs2_decode_location($sp->previous_location);
			if($prev_location) {
				$prev_location = $prev_location->name;
			}

			$info = array(
				'spart_id'		=> $sp->id,			// 시리얼장비 ID
				'category_id'	=> $sp->part->category->id,
				'part_id'		=> $sp->part->id,
				'serial_number'	=> $sp->serial_number,
				'prev_location' => $prev_location,
				'is_new'		=> $sp->is_new,
				'wpart_id'		=> $wpart->id,		// 대기장비 ID
			);

			$response->info = $info;
		}	
		
		echo json_encode($response);
	}

	// 시리얼넘버로 장비 찾기 
	public function find_by_sn($q) {
		$params = ($this->input->post()) ? $this->input->post() : $this->input->get();

		// 시리얼넘버
		$sn = urldecode($q);

		$this->load->model('work_m', 'work_model');
		$op = $this->work_model->get($params['id']);

		if( $op->type == '801' || $op->type == '802') {
			$this->load->model('transfer_m');
			$result = $this->transfer_m->findBySerialNumber($op, $sn);
		}

		$response = new stdClass;

		// 에러시 에러 문자열을 반환함, 성공시 array or object
		$response->error = (is_string($result)) ? true : false;
		$response->info = $result;

		// 에러일 경우
		if($response->error) {
			$response->error_msg = $result;
		}

		echo json_encode($response);
	}

	// 업무 장비 등록
	public function add_item() {
		$this->load->model('work_m', 'work_model');

		$input = ($this->input->post()) ? $this->input->post() : $this->input->get();

		$op = $this->work_model->get($input['id']);
		if( $op->type == '801' || $op->type == '802') {
			$this->load->model('transfer_m');
			$item = $this->transfer_m->addItem($op, $input, true);
		}

		$response = new stdClass;

		$response->error = ($item) ? false : true;
		$response->error_msg = '';

		if($item) {
			$response->id = $item->id;
		}

		echo json_encode($response);
	}

	// 업무 장비 삭제
	public function remove_item($item_id) {

	}

	// 업무 완료
	public function complete($id) {

	}
	
}

