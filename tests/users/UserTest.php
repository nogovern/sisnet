<?php
/**
 * @group User
 */

class UserTest extends PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$this->CI = &get_instance();
	}

	public function tearDown() {
		;
	}
    
    public function test() {
		$stack = array();
	    $this->assertEquals(0, count($stack));

	    array_push($stack, 'foo');
	    $this->assertEquals('foo', $stack[count($stack)-1]);
	    $this->assertEquals(1, count($stack));

	    $this->assertEquals('foo', array_pop($stack));
	    $this->assertEquals(0, count($stack));
	}

	public function testUsingModel() {
		$model = $this->CI->load->model('user_repository');
		$user_repository = $this->CI->user_repository;	// 쉬운 형태로 변경

		// $this->CI-> 형태로 불러와야 함
		$rows = $this->CI->user_repository->lists();
		$this->assertEquals( count($rows), 7);

		$this->assertFalse( $user_repository::find() );
	}

	public function testAddUser() {

	}

	public function testDoctrine2()
	{
		// $this->CI->load->library('doctrine');
	}

	/**
	 * 사무소 입력 테스트
	 */
	public function testOfficeSingleAdd()
	{
		$this->CI->load->model('office_repository');
		$office_repository = $this->CI->office_repository;

		$office1 = new Office();
		$office1->name = "본사";
		$office1->is_inventory = "N";

		$office_id = $office_repository->add($office1);

		// offices 테이블의 id 최대값이 입력 시 리턴 됨 id 와 같은지 확인
		$max_id = $office_repository->getMaxId();
		$this->assertEquals($max_id, $office_id);

		// 테스트 후 입력데이터 초기화 (truncate???)
		// 
		// $office_repository->truncate();
	}

	/**
	 * 거래처 입력 테스트
	 */
	public function testCustomerAdd()
	{
		
	}

}