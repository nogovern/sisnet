<?php
//use Entity;

class CommonTest extends PHPUnit_Framework_TestCase {
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

	// DQL 테스트
	public function testDQL() {
		$dql = "select p, sl FROM Entity\Part p JOIN p.stock_list sl";
		$rows = $this->em->createQuery($dql)->getResult();

		$this->assertGreaterThan(0, count($rows));
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
