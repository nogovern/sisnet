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
		$inventory = $this->CI->inventory_m->get(1);
		$this->assertInstanceOf('Entity\Inventory', $inventory);

		// 장비
		$this->CI->load->model('part_m');
		$part = $this->CI->part_m->get(1);
		$this->assertInstanceOf('Entity\part', $part);

		// 1번 창고에 1번 부품을 5개 입고한다
		$result = $inventory->add( $part, 5);
		$this->assertTrue($result);

		// 1번 부품 재고량 확인 
		// 원수량 + 5 == 재고량
	}

	// 출고 테스트
	public function testStockOut() {

	}
	
}
