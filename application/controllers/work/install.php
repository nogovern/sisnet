<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 설치 업무 컨트롤러
*/
class Install extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('work_m', 'work_model');
		$this->em = $this->work_model->getEntityManager();

		$this->load->library('auth');
	}

	public function index() {
		echo "Welcome to 설치 업무 메인";
	}

	public function open() {
		echo '설치 업무를 시작합니다';
	}

	public function close() {
		echo '설치 업무를 종료합니다';
	}

}
