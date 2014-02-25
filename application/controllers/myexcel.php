<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myexcel extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('excel');
	}

	public function index()
	{
		echo "Excle main";
	}

	// 엑셀 파일 다운로드
	public function download($objExcel, $save_filename) {
		// redirect output to client browser
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/octet-stream");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $save_filename. '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
		$objWriter->setOffice2003Compatibility(true);
		$objWriter->save('php://output');
	}

	// 폐기 대기 장비 목록
	public function destroy() {
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
		$op = $this->work_m->get(24);

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
		$this->download($objPHPExcel, $save_filename);		
	}

	// 예제 1
	public function ex1() {
		// Create new PHPExcel object
		// echo date('H:i:s') . " Create new PHPExcel object\n";
		$objPHPExcel = new PHPExcel();

		// Set properties
		// echo date('H:i:s') . " Set properties\n";
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
		$objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
		$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
		$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
		$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");

		// echo date('H:i:s') . " Add some data\n";
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Hello');
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'world!');
		$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Hello');
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'world!');

		// Rename sheet
		// echo date('H:i:s') . " Rename sheet\n";
		$objPHPExcel->getActiveSheet()->setTitle('Simple');

				
		// Save Excel 2007 file
		// echo date('H:i:s') . " Write to Excel2007 format\n";
		// $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		// $objWriter->save(str_replace('.php', '.xlsx', __FILE__));

		// echo date('H:i:s') . " Done writing file.\r\n";

	}


	////////////////////
	// 엑셀 파일 읽기 예제
	////////////////////
	public function read() {

		// $file_path = realpath(BASEPATH . '../assets/template/test2.xlsx');
		$file_path = realpath(BASEPATH . '../assets/template/test.xls');

		try {
			$inputFileType = PHPExcel_IOFactory::identify($file_path);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = PHPExcel_IOFactory::load($file_path);
		} catch(Exception $e) {
			die("Error loadiong excel fiel....");
		}

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		//  Loop through each row of the worksheet in turn
		for ($row_num = 1; $row_num <= $highestRow; $row_num++){ 
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row_num . ':' . $highestColumn . $row_num,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);
		    //  Insert row data array into your database of choice here
		    gs2_dump($rowData);
		}

		echo $row_num;
	}

	////////////////////
	// 설치확인서 불러서 편집
	////////////////////
	public function edit() {

		// $file_path = realpath(BASEPATH . '../assets/template/test2.xlsx');
		$file_path = realpath(BASEPATH . '../assets/template/install.xls');

		try {
			$inputFileType = PHPExcel_IOFactory::identify($file_path);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = PHPExcel_IOFactory::load($file_path);
		} catch(Exception $e) {
			die("Error loadiong excel fiel....");
		}

		$value = "◎ ( 설치  ,  철수 )일자 :             년         월         일                                   ◎ 영업일자 :          년        월        일";

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0); 
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A8', 'Hello');

		echo $row_num;
	}
}

