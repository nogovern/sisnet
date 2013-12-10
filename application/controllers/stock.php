<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 재고 컨트롤러
*/
class Stock extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('stock_m', 'stock_model');

		// 프로파일링
		// $this->output->enable_profiler(TRUE);
	}

	public function index() {
		$this->lists();
	}

	// 전체 장비 재고 리스트
	public function lists() {

		$data['title'] = '재고------------------^';

		$em = $this->stock_model->getEntityManager();
		$data['type'] = '';
		$data['rows'] = $em->getRepository('Entity\Part')->findAll();

		$this->load->view('stock_list', $data);
	}

	// 창고별 장비 재고
	public function listByInventory($inven_id) {
		$data['title'] = '창고별 재고 상황';
		$data['current'] = 'page-stock';

		$this->load->model('inventory_m', 'inventory_model');
		$inventory =  $this->inventory_model->get($inven_id);
		$data['inven'] = $inventory;				// Inventory 객체
		$data['rows'] = $inventory->getStockList();

		$this->load->view('stock_list_by_inventory', $data);
	}

	public function add() {
		//////////
		// 폼  //
		//////////
		$this->load->helper('form');
		$this->load->library('form_validation');

		$em = $this->stock_model->getEntityManager();
		// 장비 목록
		$parts = $em->getRepository('Entity\Part')->findAll();
		$option_parts = array();
		$option_parts[0] = "-- 장비를 선택하세요 --";
		foreach($parts as $part) {
			$option_parts[$part->id] = $part->name;
		}

		// 창고 목록
		$inventories = $em->getRepository('Entity\Inventory')->findAll(); 
		$option_inventories = array();
		$option_inventories[0] = "-- 창고를 선택하세요 --";
		foreach($inventories as $inven) {
			$option_inventories[$inven->id] = $inven->name;
		}


		$data['title'] = '재고 수동 입력';
		$data['form_part_select'] = form_dropdown('part_id', $option_parts, 0, 'id="part_id" class="form-control"');
		$data['form_inventory_select'] = form_dropdown('inventory_id', $option_inventories, 0, 'id="inventory_id" class="form-control"');

        // 에러 구분자 UI 설정
		$this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '<button type="button" class="close" aria-hidden="true">&times;</button></div>');

		// 규칙 설정
		$this->form_validation->set_rules('part_id', '장비 모델명', 'required|greater_than');
		$this->form_validation->set_rules('inventory_id', '창고', 'required|greater_than');
		$this->form_validation->set_rules('qty_minumum', '기준수량', 'numeric');
		$this->form_validation->set_rules('qty_new', '신품 수량', 'numeric');
		$this->form_validation->set_rules('qty_used', '중고 수량', 'numeric');

		$this->form_validation->set_message('greater_than', '%s 필드는 필수 항목 입니다');

		if($this->form_validation->run() === FALSE){
			$this->load->view('layout/header');
			$this->load->view('layout/navbar');
			$this->load->view('stock_manual_add_form', $data);
			$this->load->view('layout/footer');
		} 
		else 
		{
			var_dump($_POST);

			// -- 라이브러리 화 해야 하는데 어디에 하지????
			// 재고가 등록되어 있는지 여부 확인
			$stock = $em->getRepository('Entity\Stock')->findBy(array(
				'part' => (int)$this->input->post('part_id'),
				'inventory' => (int)$this->input->post('inventory_id')
			));

			$has_stock = (count($stock)) ? TRUE : FALSE;

			// 재고 등록되어 있지 않으면 등록
			// 수량이 모두 0이면 등록 하지 않음
			if($has_stock === FALSE) {
				if(
					$this->input->post('qty_minimum') == 0  
					&& $this->input->post('qty_new') == 0  
					&& $this->input->post('qty_used') == 0  
					&& $this->input->post('qty_s100') == 0  
					&& $this->input->post('qty_s400') == 0 )
				{
					trigger_error('수량이 모두 0 일수 없습니다');
					exit;
				}

				$part = $em->getRepository('Entity\Part')->find((int)$this->input->post('part_id'));
				$inventory = $em->getRepository('Entity\Inventory')->find((int)$this->input->post('inventory_id'));

				unset($stock);
				$stock = new Entity\Stock();
				$stock->setPart($part);
				$stock->setInventory($inventory);

				$stock->setQtyMinimum((int)$this->input->post('qty_minimum'));
				$stock->setQtyNew((int)$this->input->post('qty_new'));
				$stock->setQtyUsed((int)$this->input->post('qty_used'));
				$stock->setQtyS100((int)$this->input->post('qty_s100'));
				$stock->setQtyS400((int)$this->input->post('qty_s400'));

				$em->persist($stock);
				$em->flush();

				redirect('/stock');
			}
		}

	}
}