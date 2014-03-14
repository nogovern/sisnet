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
		;
	}

	//////////////
	// 철수 업무	 //
	//////////////
	public function testRevertOperationStatus() {
		echo 'here!!!';
		$this->CI->load->model('work_m', 'work_model');
		$op = $this->CI->work_model->get(4);
		// $this->assertTrue(FALSE);

		$op->setStatus('3');
		$this->assertEquals('장광희', $op->user->name);

		// $this->em->persist($op);
		// $this->em->flush();
	}

	/////////////////////
	// 시어얼 관리장비 수동 입력 //
	/////////////////////
	public function testRegisterSerialPart() {

		$this->CI->load->model('part_m', 'part_model');
		$part = $this->CI->part_model->get(3);
		//$this->assertEquals($part->name, 'IBM 8200');
		// $this->assertEquals($part->type, 1);			// 시리얼 장비인가?

		// 등록 데이터
		$data = array(
			'serial_number' => 'zzzz-000003',
			'part_id'	=> $part->id,
			'memo'	=> '테스트 데이터',
			'is_new'	=> 'Y',
			'is_valid'	=> 'Y',
			'current_location'	=> 'O@1',
			'previous_location'	=> 'O@9'
		);

		// $entry = $this->CI->part_model->addSerialPart($data);		// SerialPart Entity Object
		$entry = new ArrayObject;
		$result = ($entry instanceof Entity\SerialPart);
		//$this->assertTrue($result);
	}



	////////////
	// 입고 업무 //
	////////////
	public function testEnterBefore() {
		$temp = $this->em->getRepository('Entity\OperationTempPart')->findAll();
		$this->assertEquals($temp, array());
	}

	// 납품처 -> 수량장비 등록
	public function testRegisterCountPartForDelivery() {
		$this->CI->load->model('work_m', 'work_model');
		$op = $this->CI->work_model->get(4);
		$this->assertTrue($op instanceof Entity\Operation);

		$part = $op->getItem()->part;

		// 수량 비교용 
		$request_qty = $op->getItem()->qty_request;

		//$this->CI->work_model->addTempItem($op, $part, 'A4');

	}

	// 납품처 -> 시리얼 장비 등록
	public function testRegisterSerialPartForDelivery() {
		$this->CI->load->model('work_m', 'work_model');
		$op = $this->CI->work_model->get(1);
		$this->assertTrue($op instanceof Entity\Operation);


		$part = $op->getItem()->part;
		//$this->CI->work_model->addTempItem($op, $part, '#ZZZZZZ', TRUE);
	}
	
	
	//////////////
	// 설치 프로세스 //
	//////////////
	// - 장비 수량이 "설치전 " 수량으로 이동
	// - 비가용 수량으로 전환
	// - 설치후에는 비가용 수량에서 출고 


	// 특정 업무 삭제
	public function testRemoveOperation() {
		$op = $this->CI->work_model->get(64);

		$this->em->remove($op);
		$this->em->flush();
	}

}
