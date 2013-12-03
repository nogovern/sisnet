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

	// 입고 테스트	
	public function testStockIn() {

		// from where??? (어디서 부터)
		
		// 창고
		$this->CI->load->model('inventory_m');
		$inventory = $this->CI->inventory_m->get(2);
		$this->assertInstanceOf('Entity\Inventory', $inventory);

		// 장비
		$this->CI->load->model('part_m');
		$part = $this->CI->part_m->get(3);
		$this->assertInstanceOf('Entity\part', $part);

		// 1번 창고에 1번 부품을 5개 입고한다
		// Stock 객체를 리턴한다
		$stock = $inventory->add( 'new', $part, 1);

		// 저장
		$this->em->persist($stock);
		$this->em->flush();

		$this->assertEquals(20, $stock->qty_new);

		////////////////
		// 로그 기록  //
		////////////////

	}

	// 출고 테스트
	public function testStockOut() {
		
		// 창고 (from)
		$this->CI->load->model('inventory_m');
		$inventory = $this->CI->inventory_m->get(2);

		// 장비
		$this->CI->load->model('part_m');
		$part = $this->CI->part_m->get(3);

		// 출고 장소
		 
		////////////////
		// 로그 기록  //
		////////////////

	}

	


	// 교체 프로세스
	// 출고 -> 입고
	// 
	
}
