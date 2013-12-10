<?php
//use Entity;

class WorkTest extends PHPUnit_Framework_TestCase {
	protected $em = NULL;
	
	public function setUp()
	{
		$this->CI = &get_instance();

		// doctrine 초기화
		if(is_null($this->em)){
			$this->CI->load->library('doctrine');
			$this->em = $this->CI->doctrine->em;
		}
	}

	public function tearDown() {

	}

	public function test1() {
		$this->assertTrue(FALSE);
	}

	////////////
	// 입고 업무 //
	////////////
	
	
	//////////////
	// 설치 프로세스 //
	//////////////
	// - 장비 수량이 "설치전 " 수량으로 이동
	// - 비가용 수량으로 전환
	// - 설치후에는 비가용 수량에서 출고 

	

	//////////////
	// 철수 업무	 //
	//////////////


}
