<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 	폐기 컨트롤러
*/
class Destroy extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
		$this->load->model('waitpart_m', 'waitpart_model');
	}

	public function index() {
		$this->lists();
	}

	public function lists($page = 1) {
		$this->load->helper('form');

		$data['title'] = '폐기 업무';
		$data['current'] = 'page-destroy';

		///////////////
		// 검색 조건
		///////////////
		$criteria = array();
		
		// 상태
		if($this->input->get('status')) {
			$criteria['status'] = $this->input->get('status');
		}

		// 형태
		if($this->input->get('type')) {
			$criteria['type'] = $this->input->get('type');
		}

		// 사무소 - GET 유무 확인시 없을떄 false 로 타입까지 비교해야 함
		if($this->input->get('off_id') === false) {
			$criteria['office'] = (gs2_user_type() == '1') ? $this->session->userdata('office_id') : 0;
		} else {
			$criteria['office'] = $this->input->get('off_id');
		}

		// pagination 초기화
		$config = $this->work_model->setPaginationConfig('work/destroy/lists/');

		$data['rows'] = $this->work_model->getOperations(GS2_OP_TYPE_DESTROY, $criteria, GS2_LIST_PER_PAGE, $page);
		// 총 결과수
		$total_rows = $this->work_model->numRows(GS2_OP_TYPE_DESTROY, $criteria);
		$config['total_rows'] = $total_rows;

		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['total_rows'] = $total_rows;

		// ===============
		//  필터링 데이터
		// ===============
		$this->load->helper('form');

		// 진행상태
		$data['status_filter'] = form_dropdown('status', gs2_op_status_list('2'), $this->input->get('status'), 'id="status_filter" class="form-control"');

		// 작업형태
		$type_list = array(
			'0'	=> '-- 전체 --',	
			'601'	=> '폐기-승인',	
			'602'	=> '폐기-출고',	
		);
		
		$data['type_filter'] = form_dropdown('type', $type_list, $this->input->get('type'), 'id="type_filter" class="form-control"');

		// 담당 사무소
		$this->load->model('office_m', 'office_model');
		$arr_office = gs2_convert_for_dropdown($this->office_model->getList());
		$arr_office['0'] = '--전체--';
		$data['office_filter'] = form_dropdown('off_id', $arr_office, $criteria['office'], 'id="office_filter" class="form-control"');

		/////////////////////////// modal 요청서 용 ////////
		// 사무소 select 생성
		$data['select_office'] = form_dropdown('select_office', $arr_office, $this->session->userdata('office_id'), 'id="select_office" class="form-control required"');
		
		$this->load->view('work/work_destroy_list', $data);
	}

	////////////
	// 요청 등록
	////////////
	public function register() {
		// $post_data = $this->input->post();


		if(!count($this->input->post())) {
			echo '작업중';
		} else {

			$post_data['op_type'] =  $this->input->post('op_type');
			$post_data['office_id'] = $this->input->post('select_office');
			$post_data['date_request'] = date("now");


			// 업무 생성
			$op = $this->work_model->createOperation( $this->input->post('op_type'), $post_data);

			// 로그 기록
			$log_data = array(
				'type'		=> '1',
				'content'	=> '폐기 업무가 생성되었음',
				'event'		=> '생성'
			);
			$this->work_model->addLog($op, $log_data, TRUE);

			redirect('work/destroy');
		}

	}

	public function view($id) {
		$this->load->helper('form');

		$data['title'] = "폐기 업무 상세 보기";
		$data['current'] = 'page-destroy';
		$data['_config'] = $this->config->item('gs2');

		$op = $this->work_model->get($id);
		$data['op'] = $op;

		// 장비 카테고리 dropdown
		$this->load->model('category_m', 'category_model');
		$cats = $this->category_model->getValidPartCategories();
		$cats = gs2_convert_for_dropdown($cats);
		$data['select_category'] = form_dropdown('select_category', $cats, 0, 'id="select_category" class="form-control"');

		$this->load->view('work/work_destroy_view', $data);
	}

	public function update($id) {
		echo '작업중';
	}

	

	// 폐기 승인 목록에 등록된 아이템 삭제
	public function removeItem() {
		$em = $this->work_model->getEntityManager();

		$op = $this->work_model->get($this->input->get("id"));
		$item = $em->getReference('Entity\OperationPart', $this->input->get("item_id"));

		// extra 필드에 대기장비 id 저장되어 있음
		// $this->waitpart_model->update($item->extra, array('status' => '1'));
		
		$wp = $this->waitpart_model->get($item->extra);
		$qty = $item->getQtyRequest();
		$wp->minus($qty, 2);
		$wp->add($qty, 1);
		$this->work_model->_add($wp);
			
		$result['msg'] = sprintf('%s 장비를 목록에서 삭제하였습니다', $item->part_name);
		$this->work_model->removeItem($item, true);

		echo json_encode($result);
	}
	

	// 폐기 출고 장비 스캔
	public function scanItem() {

	}

	public function excel_download($id) {
		$this->load->library('excel');
		
		$objPHPExcel = new PHPExcel();

		// Set properties
		$objPHPExcel->getProperties()->setCreator("Sisnet Service");
		$objPHPExcel->getProperties()->setLastModifiedBy("Sisnet Service");
		$objPHPExcel->getProperties()->setTitle("페기장비 목록 - 승인용");
		$objPHPExcel->getProperties()->setSubject("페기장비 목록 - 승인용");
		$objPHPExcel->getProperties()->setDescription("페기장비 목록 - 승인용, generated using PHP classes.");

		$headers = array('타입', 'Serial Number', '장비종류', '모델명', '상태', '수량', '직전점포', '등록일');

		// 헤더 설정
		$nCol = 'A';
		$nRow = 1;
		$objPHPExcel->setActiveSheetIndex(0);
		foreach($headers as $h) {
			$objPHPExcel->getActiveSheet()->SetCellValue($nCol . $nRow, $h);
			$nCol++;
		}

		// 데이터 채우기
		$this->load->model('work_m');
		$op = $this->work_m->get($id);

		$rows = $op->getItems();
		
		foreach($rows as $row) {
			$nRow++;
			$prev_data = ($row->prev_location) ? gs2_decode_location($row->prev_location)->name : '';
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$nRow, gs2_part_type($row->part_type));
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$nRow, $row->serial_number);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$nRow, $row->part->category->name);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$nRow, $row->part->name);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$nRow, '중고');
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$nRow, $row->qty_request);
			$objPHPExcel->getActiveSheet()->SetCellValue('G'.$nRow, $prev_data);
			$objPHPExcel->getActiveSheet()->SetCellValue('H'.$nRow, date("Y-m-d"));
		}

		// 타이틀
		$objPHPExcel->getActiveSheet()->setTitle('폐기장비목록');
		$save_filename = 'test_' . date('Ymd') . '.xls';
		
		// 엑셀파일 다운로드
		// $this->download($objPHPExcel, $save_filename);
		// redirect output to client browser
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/octet-stream");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $save_filename. '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setOffice2003Compatibility(true);
		$objWriter->save('php://output');
	}

}
