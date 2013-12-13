<?php
//use Entity;

class WorkTest extends PHPUnit_Framework_TestCase {
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

	public function test1() {
		$this->assertTrue(FALSE);
	}

	////////////
	// 입고 업무 //
	////////////
	
	
	//////////////
	// 설치 프로세스 //
	//////////////
	// - 장비 수량이 "설치전 " 수량으로 이동
	// - 비가용 수량으로 전환
	// - 설치후에는 비가용 수량에서 출고 

	

	//////////////
	// 철수 업무	 //
	//////////////

	//////////////
	// 카테고리 테스트
	//////////////
	public function testCategory() {
		$this->repo = $this->em->getRepository('Entity\Category');
		$cats = $this->repo->findAll();

		echo count($cats);
	}

	public function testInsertCategory() {
		$this->repo = $this->em->getRepository('Entity\Category');
		$parent = $this->repo->find(2);

		$cat1 = new Entity\Category();
		$cat1->name = "현대";
		$cat1->parent = $parent;
		$cat1->status = "Y";
		$cat1->date_register = new DateTime("now");

		
		$cat2 = new Entity\Category();
		$cat2->name = "GM대우";
		$cat2->parent = $parent;
		$cat2->status = "Y";
		$cat2->date_register = new DateTime("now");

		// $this->em->persist($cat1);
		// $this->em->persist($cat2);

		// $this->em->flush();
	}

	public function testCategoryHirerachy() {
		$stack = array();

		$this->repo = $this->em->getRepository('Entity\Category');
		$obj = $this->repo->find(6);

		array_push($stack, $obj->id);
		array_push($stack, $obj->parent->id);		// 부모

		$stack = array_reverse($stack);
		echo join('->', $stack);
	}

	public function testCarCategory() {
		$this->repo = $this->em->getRepository('Entity\Category');
		$parent = $this->em->getReference('Entity\Category', 2);

		$children = $this->repo->findBy(array('parent' => $parent));

		echo count($children);
	}

	public function testInputStore() {
		$store = new Entity\Store;

		$store->name = "뉴타운점";
		$store->owner_name ="장광희";
		$store->join_type = "거저";
		$store->date_register = new DateTime("now");
		$store->tel = "02-9876-1234";

		// $this->em->persist($store);
		// $this->em->flush();

	}


}
