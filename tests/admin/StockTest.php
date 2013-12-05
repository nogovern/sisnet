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

	/*
	장비 - 창고 조합의 재고 데이터가 존재하는지 확인
	 */
	public function testStockExist() {
		$part_id = 1;
		$inventory_id = 1;
		$stock = $this->em->getRepository('Entity\Stock')->findBy(array(
			'part' => $part_id,
			'inventory' => $inventory_id
		));

		$cnt_stock = count($stock);
		$has_stock = count($stock) ? TRUE : FALSE;

		// $this->assertEquals(get_class($stock), 'Entity\Stock');
		$this->assertTrue($has_stock);
	}

	//////////////////////////
	// 특정 창고의 특정 상품 재고량 얻기 //
	//////////////////////////
	public function testGetStockQuantityOfInventory() {
		$sql = "SELECT p FROM Entity\Part p JOIN p.stock_list s WHERE s.inventory = 1 AND p.id = 3";
		$query = $this->em->createQuery($sql);
		$parts = $query->getResult();

		echo $parts[0]->getNewTotal();
	}

	///////////////////////////////////////////
	// 특정 창고의 상품 목록 - array of part objects //
	///////////////////////////////////////////
	public function testPartListOfInventory() {
		$sql = "SELECT p FROM Entity\Part p JOIN p.stock_list s WHERE s.inventory = 1";
		$query = $this->em->createQuery($sql);
		$parts = $query->getResult();
	}

	///////////////
	// 입고 테스트	 //
	///////////////
	public function testStockIn() {

		// from where??? (어디서 부터)
		
		// 창고
		$this->CI->load->model('inventory_m');
		$inventory = $this->CI->inventory_m->get(1);
		$this->assertInstanceOf('Entity\Inventory', $inventory);

		// 장비
		$this->CI->load->model('part_m');
		$part = $this->CI->part_m->get(7);
		$this->assertInstanceOf('Entity\part', $part);

		// 1번 창고에 1번 부품을 5개 입고한다
		// Stock 객체를 리턴한다
		$result = $inventory->add( 'new', $part, 1);

		// 저장
		if($result) {
			// $this->em->persist($stock);
			$this->em->flush();
		}

		$this->assertEquals(20, $result->qty_new);

		////////////////
		// 로그 기록  //
		////////////////

	}

	// 출고 테스트
	public function testStockOut() {
		
		// 창고 (from)
		$this->CI->load->model('inventory_m');
		$inventory = $this->CI->inventory_m->get(1);

		// 장비
		$this->CI->load->model('part_m');
		$part = $this->CI->part_m->get(7);

		// 출고
		$result = $inventory->out( 'new', $part, 3);

		// 저장
		if($result) {
			$this->em->flush();
		}
		 
		////////////////
		// 로그 기록  //
		////////////////

	}

}
