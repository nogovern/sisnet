<?php
//use Entity;

class StockTest extends PHPUnit_Framework_TestCase {
	protected $em = NULL;
	protected $cnt_stock = 0 ;		// 디버깅용 InventoryPartAssociation 데이터 갯수
	
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
			echo "\n Qty : " . $stock->qty_new; // 재고 수량
			$invens = $stock->inventory;		// 창고
		}
		echo "\n==============\n";
	}
	
	public function testStock() {
		$this->em->clear();
		$repo = $this->em->getRepository('Entity\Stock');
		$rows = $repo->findAll();

		$this->cnt_stock = count($rows);

		$this->assertEquals(count($rows), 5);
	}

	public function testDQL() {
		$dql = "select p, sl FROM Entity\Part p JOIN p.stock_list sl";
		$rows = $this->em->createQuery($dql)->getResult();

		echo count($rows);
	}

	public function testUseRawSQLInDoctrine() {
		$this->em->clear();
		$conn = $this->em->getConnection();

		$sql = "select * from GS2_PARTS g0_	INNER JOIN GS2_INVENTORY_PART g1_ ON g0_.id = g1_.part_id "
			. " where g0_.id = 1";
		// $rs = $this->em->getConnection()->exec($sql);
		$sql0 = "select * from GS2_INVENTORY_PART";

		$rows = $conn->fetchAll($sql0);
		echo count($rows) . "\n";
	}

	public function testUsingCI() {
		$query = $this->CI->db->query("SELECT * FROM GS2_INVENTORY_PART");
		$rows = $query->result();
	}

	public function testAddStock() {
		$part = $this->em->getRepository('Entity\Part')->find(3);
		$inventory = $this->em->getRepository('Entity\Inventory')->find(1);

		// echo get_class($inventory);

		$stock = new Entity\Stock();
		$stock->setPart($part);
		$stock->setInventory($inventory);

		$stock->setQtyMinimum(0);
		$stock->setQtyNew(3);
		$stock->setQtyUsed(999);

		if(0){
			$this->em->persist($stock);
			$this->em->flush();
		}

		// Stock 테이블 행수 
		$repo = $this->em->getRepository('Entity\Stock');
		$rows = $repo->findAll();

		$this->assertEquals( count($rows), 5);
	}

	/*
	장비의 재고량 알기
	 */
	public function testPartStock() {
		// 값 1개
		$dql = "select sum(s.qty_new) AS qyt_total FROM Entity\Stock s " .
				"WHERE s.part = 1 AND s.inventory = 1";
		$result = $this->em->createQuery($dql)->getSingleScalarResult();

		// 장비별 재고량
		$dql = "select sum(s.qty_new) AS qyt_total FROM Entity\Stock s " .
				"GROUP BY s.part";
		$result = $this->em->createQuery($dql)->getResult();

		// 조인이용한 장비별 재고량 출력
		$dql = "select p.name, sum(s.qty_new) AS total_new, sum(s.qty_used) AS total_used FROM Entity\Part p " .
				"JOIN p.stock_list s GROUP BY s.part, p.name";
		$result = $this->em->createQuery($dql)->getResult();
		// var_dump($result);
	}
}
