<?php
//use Entity;

class StockTest extends PHPUnit_Framework_TestCase {
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

	public function testPartList() {
		$repo = $this->em->getRepository('Entity\Part');
		$items = $repo->findAll();

		$count = count($items);
		$this->assertEquals($count, 4);
	}

	public function testStockFromPart() {
		$repo = $this->em->getRepository('Entity\Part');
		$items = $repo->find(1);

		
		$stocks = $items->getStockList();
		foreach($stocks as $stock) {
			echo "\n qyt : " . $stock->qty_new;
			$invens = $stock->inventories;
			echo " -- " . $invens->name;
		}
		echo "\n";
	}

	
	public function testStock() {
		$this->em->clear();
		$repo = $this->em->getRepository('Entity\Stock');
		$rows = $repo->findAll();

		$this->assertEquals(count($rows), 3);
	}

	public function testDQL() {
		$dql = "select p, sl FROM Entity\Part p JOIN p.stock_list sl";
		$rows = $this->em->createQuery($dql)->getResult();

		foreach($rows as $row) {
			echo __METHOD__ . " " . count($rows);
		}
	}

	public function testUseRawSQLInDoctrine() {
		$this->em->clear();
		$conn = $this->em->getConnection();

		$sql = "select * from GS2_PARTS g0_	INNER JOIN GS2_INVENTORY_PART g1_ ON g0_.id = g1_.part_id "
			. " where g0_.id = 1";
		// $rs = $this->em->getConnection()->exec($sql);
		$sql0 = "select * from GS2_INVENTORY_PART";

		$rows = $conn->fetchAll($sql0);
		echo __METHOD__ . " " . count($rows);
	}

	public function testUsingCI() {
		$query = $this->CI->db->query("SELECT * FROM GS2_INVENTORY_PART");
		$rows = $query->result();

		// echo $this->CI->db->last_query();
		//var_dump($rows);
	}
}
