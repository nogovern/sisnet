<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* File 모델 (첨부 파일)
*/
class File_m extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_operation_files');
		$this->setEntityName('OperationFile');

		// 업무 모델 로드
		$this->load->model('work_m', 'work_model');
	}

	// upload option 설정
	public function setUploadOption() {
		$upload_path = BASEPATH .'../assets/files/';
		if(!file_exists($upload_path)) {
			if(!mkdir($upload_path, 0770)) {
				die($upload_path . " 파일 저장 폴더 생성 실패: 권한을 확인 또는 관리자에게 문의하세요.");
			};
		}

		$config = array();
		$config['upload_path'] = BASEPATH .'../assets/files/';
		$config['allowed_types'] = '*';
		$config['max_size'] = intval(GS2_MAX_FILE_SIZE) * 1024;
		$config['encrypt_name'] = TRUE;

		return $config;
	}

	// 생성
	function create($data, $do_flush = FALSE) {
		$file = new Entity\OperationFile;
		
		$op = $this->em->getReference('Entity\Operation', $data['op_id']);

		$file->setOperation($op);
		$file->setGubun($data['gubun']);
		$file->org_name 	= $data['orig_name'];
		$file->save_name 	= $data['file_name'];
		$file->size 		= $data['file_size'];
		$file->file_type 	= $data['file_type'];
		$file->setDateRegister();

		$this->em->persist($file);

		if($do_flush) {
			$this->em->flush();
		}

		return $file;
	}

	// 생성 2
	function add($data, $do_flush = FALSE) {
		$this->create($data, $do_flush);
	}

	// 삭제
	// 기본적으로 업무 삭제 시 자동적으로 삭제된다.	
	function delete($file_id) {
		;
	}

	// 수정
	public function update($id, $data, $do_flush = FALSE) {
		$store = $this->get($id);

		// 매직 메소드 사용하여 단순화 함
		foreach($data as $key => $val) {
			if($key == 'id') {
				continue;
			}

			$this->$key = $val;
		}

		$this->em->persist($store);

		if($do_flush) {
			$this->em->flush();
		}

		return $store;
	}

	
}

