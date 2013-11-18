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
		$this->CI->load->library('doctrine');
	}
}