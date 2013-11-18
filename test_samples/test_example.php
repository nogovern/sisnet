<?php
// use Doctrine\ORM\Tools\Setup;
// use Doctrine\ORM\EntityManager;

// require_once "vendor/autoload.php";

class StackTest extends PHPUnit_Framework_TestCase {
	
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

		// $this->CI-> 형태로 불러와야 함
		$this->assertTrue( $this->CI->user_repository->getTrue());

		$rows = $this->CI->user_repository->lists();

		$this->assertEquals( count($rows), 7);
		// $class = new AClass;
		// $this->assertEquals(TRUE, $class->ret());
	}
}