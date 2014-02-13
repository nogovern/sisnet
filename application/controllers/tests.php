<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 테스트 컨트롤러
 */
ini_set("display_errors", 1);
error_reporting(E_ALL);

class Tests extends CI_Controller {

	public function __construct() {
		parent::__construct();

		header('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">');
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
}