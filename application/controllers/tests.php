<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * 테스트 컨트롤러
 */
ini_set("display_errors", 1);
error_reporting(E_ALL);

class Tests extends CI_Controller {
	private $em = NULL;

	public function __construct() {
		parent::__construct();

		// 기본 work 모델 로드 & EntityManager 정의
		$this->load->model('work_m', 'work_model');
		$this->em = $this->work_model->getEntityManager();
	}

	public function index() {
		echo '<h1>테스트용 컨트롤러</h1>';
	}

	public function bootstrap() {
		$this->load->view("sample/bootstrap.html");
	}

	/**
	 * colorbox 테스트 페이지
	 */
	public function colorbox()
	{
		$this->load->view('sample/colorbox.html');
	}

	// 폼 샘플 
	public function form_layout() {
		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('sample/form_layout.html');
		$this->load->view('layout/footer');
	}

	// 여러가지 element 테스트
	public function page1() {
		$this->load->model('part_m');

		$this->load->helper('form');

		$data['title'] = '테스트용 sample 페이지';

		$this->load->view('sample/sample_form', $data);
	}

	// 부품 선택
	public function page2() {
		$url =  site_url('ajax/response/' . "?a=1&b=2");
		// echo str_replace('?', '', $url);

		$this->load->helper('form');

		$this->load->model('part_m');

		$em = $this->part_m->getEntityManager();
		$parent = $em->getRepository('Entity\Category')->find(1);

		$cats = $em
					->getRepository('Entity\Category')
					->findBy(
						array('parent' => $parent),
						array('id' => 'ASC')				// order by 
					);

		$data['cats'] = $cats;
		$data['title'] = '테스트 장비 선택 페이지';

		$this->load->view('sample/part_select_form', $data);
	}

	// 
	public function page3()
	{	
		$data['json'] = json_encode(gs2_category_parts());

		$this->load->view('layout/header');
		$this->load->view('layout/navbar');
		$this->load->view('sample/form_layout.html', $data);
		$this->load->view('layout/footer');
	}

	public function opnumber() {
		$this->load->model('work_m');


		$on = $this->work_m->getMaxOperationNumber();
		echo $on . '<br>';

		echo $this->work_m->makeOperationNumber();
	}

	public function modal() {
		$this->load->view('sample/modal_sample');
	}

	public function hujum() {
		$this->load->model('part_m');
		$em = $this->part_m->getEntityManager();

		$item = new Entity\RestPart;

		$op = $em->getReference('Entity\Operation', 1);
		$item->setOperation($op);

		$store = $em->getReference('Entity\Store', 1);
		$item->setStore($store);

		$part = $em->getReference('Entity\Part', 1);
		$item->setPart($part);

		$item->setQty(5);
		$item->setDateRegister();

		// $em->persist($item);
		// $em->flush();

		echo 'saved';
	}

	public function group() {
		$this->load->model('part_m');
		$em = $this->part_m->getEntityManager();


		for($i = 1; $i < 5; $i++) {
			$group = new Entity\PartGroup;

			$group->setName("그룹A");

			$part = $em->getReference('Entity\Part', $i);
			$group->setPart($part);

			$group->setQty($i);

			$em->persist($group);

		}

		$em->flush();
	}

	// 방문자 변경 용 작업자 dropdown 용 배열 생성
	public function operator() {
		$this->load->model('user_m');

		$rows = $this->user_m->getOfficeUsers();

		gs2_dump($rows);
		$this->load->helper('form');

		echo form_dropdown('workers', $rows, 22);

		foreach($rows as $row) {
			// doctrine debug dump
			// \Doctrine\Common\Util\Debug::dump($row);
		}
	}

	// 엑셀 읽기 테스트
	public function excel() {
		$this->load->library('excel');

		$objPHPExcel = PHPExcel_IOFactory::load(BASEPATH . '../assets/files/test.xlsx');

		$sheet = $objPHPExcel->getActiveSheet()->toArray(NULL, TRUE, TRUE, TRUE);

		gs2_dump($sheet);
	} 

	// Excel 템플릿 파일을 기반으로 수정하여 download 받게 하는 예제
	public function excel_edit() {
		$this->load->library('excel');
		
		// read in the existing file
		$objPHPExcel = PHPExcel_IOFactory::load(BASEPATH . '../assets/files/test.xlsx');

		// modify/insert data in worksheet cells
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'New Data by 장광희');

		// redirect output to client browser
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/octet-stream");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="newFile.xls"');
		header('Cache-Control: max-age=0');

		$save_filename = uniqid() . '.xlsx';
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setOffice2003Compatibility(true);
		$objWriter->save('php://output');
		// $objWriter->save(BASEPATH . '../assets/files/' . $save_filename);
	}

	// 교체 업무  - 대상 업무에서 부모 or 형제 찾기
	public function replace($target_id = NULL) {
		if(!$target_id){
			echo '에러 - 자식 업무 ID 를 지정하셔야 함';
			exit;
		}

		$qb = $this->em->createQueryBuilder();
		$qb->select('t')->from('Entity\OperationTarget', 't')
			->where('t.target = :t_id')
			->andWhere("t.gubun = 'replace' ")
			->setParameter('t_id', $target_id);

		$me = $qb->getQuery()->getSingleResult();

		// 부모 찾기
		$parent = ($me) ? $me->operation : NULL;

		if($parent) {
			gs2_dump($parent->id);
		}

		// 형제 찾기
		$targets = $parent->getTargets();
		foreach( $targets as $t) {
			if($t->target->id != $me->target->id)
				$sibling = $t->target;
		}
		gs2_dump($sibling->id);

	}

	// 업무 장비 소트 
	public function sort($op_id) {
		$op = $this->em->getRepository('Entity\Operation')->find($op_id);
		$items = $op->getItems();

		echo get_class($items);

		$res1 = array();
		foreach($items as $it) {
			$res1[] = $it->part->id;
		}
		gs2_dump($res1);

		/*sorting...*/
		$arr = $items->toArray();

		uasort($arr, function($first, $second) {
			$a = (int)$first->part->id;
			$b = (int)$second->part->id;

			if($a == $b)
				return 0;
			return ($a > $b) ? -1 : 1;
		});

		$res2 = array();
		foreach($arr as $it) {
			$res2[] = $it->part->id;
		}
		gs2_dump($res2);

	}

	// jquery file upload 
	public function upload() {
		$this->load->library('upload');

		$data = array();
		$this->load->view('sample/jquery-file-upload', $data);
	}

	// 실제 파일 업로드
	public function do_upload() {
		$options = array();

		$options['script_url'] = base_url() . '/tests/do_upload';
		$options['upload_dir'] = BASEPATH . '../assets/files/';
		$options['upload_url'] = base_url() . '/assets/files/';
		$options['max_file_size'] = NULL;
		$options['mkdir_mode'] = '0775';
		$options['param_name'] = 'files';


		$this->load->library('uploadhandler', $options);
	}

	// 장비 카테고리 테스트 
	public function category() {
		$this->load->model('category_m', 'category_model');

		$arr1 = $this->category_model->getAllPartCategories();
		gs2_dump(count($arr1));
		
		$arr2 = $this->category_model->getValidPartCategories();
		gs2_dump(count($arr2));

	}

	// 이동업무 완료 테스트 
	public function move_complete($id) {
		$op = $this->work_model->get($id);

		$this->load->model('part_m', 'part_model');

		$items = $op->getItems();

		foreach($items as $item) {
			$part = $item->part;
			$recv_office = gs2_decode_location($op->work_location);

			$send_stock = $part->getStock($op->office->id);		// 송신 재고
			$recv_stock = $part->getStock($recv_office->id);	// 수신 재고

			// 재고량 변경
			$qty =  $item->getQtyScan();
			if($item->isNew()){
				$recv_stock->increase('new', $qty);
			} else {
				$recv_stock->increase('used', $qty);
			}
			$this->work_model->_add($recv_stock);

			// 시리얼장비 처리
			if($item->part_type == '1') {

				$arr['previous_location'] 	= gs2_encode_location($op->office);
				$arr['current_location'] 	= $op->work_location;
				$arr['is_valid'] 			= true;
				$arr['status'] 				= '1';
				$arr['date_enter'] 			= '';	// 입고일

				$sp = $this->part_model->updateSerialPart($item->serial_part, $arr);
			}
		}

		// 로그 기록
		$log_data = array(
			'type'		=> '1',
			'content'	=> '이동 업무 완료합니다.',
			'event'		=> '완료'
		);
		$this->work_model->addLog($op, $log_data);
	}

	///////////////////////////
	// doctrine Qeury Debug //
	///////////////////////////
	public function dql() {
		$qb = $this->em->createQueryBuilder(); 
		$qb->select("s")
			->from("Entity\SerialPart", "s");

		$query = $qb->getQuery();

		gs2_dump($query->getSQL());
		gs2_dump($query->getParameters());
	}

	public function dql_stock() {
		if(1) {
			$qb = $this->em->createQueryBuilder(); 
			$qb->select("s, p")
				->from("Entity\Stock", "s")
				->leftJoin("s.part", "p")		// JOIN
				// ->andWhere("s.office = 1")
				// ->andWhere("p.category = 18")
				->orderBy('p.id', 'ASC');

			$query = $qb->getQuery();
		} else {
			$query = $this->em->createQuery("SELECT s, p FROM Entity\Stock s JOIN s.part p ORDER BY p.id");
		}

		// $query->setFirstResult(20)->setmaxResults(20);
		$result = $query->getResult();
		echo count($result);
		$arr = array();
		foreach ($result as $idx => $row) {
			$arr[] = $row->part->id;
		}	
		gs2_dump($arr);
/*		gs2_dump($query->getSQL());
		gs2_dump($query->getParameters());
*/	
	}

	// 결과수를 계산하는 예제
	public function row_count() {
		$qb = $this->em->createQueryBuilder(); 
		$qb->select("count(s.id)")
			->from("Entity\SerialPart", "s");

		$query = $qb->getQuery();
		$count = $query->getSingleScalarResult();

		gs2_dump($query->getSQL());
		echo $count;

	}

	// 폐기,수리 대기 장비
	public function wait_part() {
		$this->load->model('waitpart_m');

		$result = $this->waitpart_m->all();
		echo count($result);

		$post['gubun']		= 'R';
		$post['op_id']		= 3;
		$post['part_id']	= 5;
		$post['qty']		= 5;
		$post['part_type']	= '1';
		$post['serial_id']	= '10';		// 시리얼넘버는 자동으로 채운다

		$new = $this->waitpart_m->create($post);
		echo $new->id;

		// $this->waitpart_m->remove($new);
		$this->em->flush();
	}

	// 대기 장비 검색
	public function wait_search() {
		$this->load->model('waitpart_m');

		$condition = array("gubun" => "D", "part" => 38, 'office' => 1);
		// $condition = array("gubun" => "D", "previous_location" => "S@7679", 'office' => 1);
		$result = $this->waitpart_m->search($condition);
		gs2_dump(count($result));

		$arr = $this->waitpart_m->getPreviousLocationArray('테스트');
		gs2_dump($arr);

		$condition = array("previous_location" => $arr);
		$result = $this->waitpart_m->search($condition);

		gs2_dump(count($result));
	}

	// 인터페이스 테스트
	public function ex_interface() {
		$this->load->model('destroy_m');

		$rows = $this->destroy_m->getList(GS2_OP_TYPE_DESTROY, array());
		echo count($rows);
	}	


	public function model($id = null) {
		$this->load->model('opitem_m');

		$result = $this->opitem_m->all($id);
		echo count($result);

	}
}


