<?php
/**
 *
 * 주의!
 * - 꼭 ../vendor/bin/phpunit 으로 실행할 것!!!
 */
class UserTest extends PHPUnit_Framework_TestCase {
	protected static $em = NULL;
	
	public function setUp()
	{
		$this->CI = &get_instance();

		//self::$em = $this->CI->load->library('doctrine');
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

	public function test2() {
		$query = $this->CI->db->query("SELECT * FROM gs_user_info");
		$rs = $query->result();
	}

	public function testAddPart() {
		$this->CI->load->library('doctrine');
		
		$em =  $this->CI->doctrine->em;
		$repo = $em->getRepository("Entity\Stock");
	}

	

}