<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myexcel extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('excel');
	}

	public function index()
	{
		$this->load->view('welcome_message');
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


		// Add some data
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

		$save_filename = 'test_' . date('Ymd') . '.xls';
		// redirect output to client browser
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/octet-stream");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' .$save_filename. '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setOffice2003Compatibility(true);
		$objWriter->save('php://output');
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

