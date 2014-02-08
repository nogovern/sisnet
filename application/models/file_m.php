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

