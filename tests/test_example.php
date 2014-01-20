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
		$model = $this->CI->load->model('user_m');

		// $this->CI-> 형태로 불러와야 함
		//$this->assertTrue( $this->CI->user_m->getTrue());

		//$rows = $this->CI->user_m->lists();

		//$this->assertEquals( count($rows), 7);
		// $class = new AClass;
		// $this->assertEquals(TRUE, $class->ret());
	}

	public function testIncreaseStock() {
		$em = $this->CI->doctrine->em;

		$part = $em->getRepository('Entity\Part')->find(38);
		$office = $em->getRepository('Entity\Office')->find(1);

		$repo = $em->getRepository('Entity\Stock');
		$stock = $repo->findOneBy(array('part'=> $part, 'office' => $office));

		$this->assertEquals(0, $stock->qty_s900);

		// 10 증가
		$stock->increase('s900', 10);
		$this->assertEquals(10, $stock->qty_s900);

		// 5 감소
		$stock->decrease('s900', 15);
		$this->assertEquals(5, $stock->qty_s900);
		

	}
}