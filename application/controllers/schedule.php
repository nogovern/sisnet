<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class Schedule extends CI_Controller
{
	public function __construct($foo = null) {
		parent::__construct();
	}

	public function index()
	{
		$prefs = array(
			'show_next_prev' => TRUE,
		);

		$this->load->library('calendar', $prefs);

		$data['title'] = '일정';
		$this->load->view('layout/header', array('title' => $data['title']));		
		echo $this->calendar->generate();
		// $this->load->view($view_url, $data);
		$this->load->view('layout/footer');
	}

	public function template($value='')
	{
		$prefs['template'] = '
		   {table_open}<table class="table table-bordered">{/table_open}

		   {heading_row_start}<tr>{/heading_row_start}

		   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
		   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
		   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

		   {heading_row_end}</tr>{/heading_row_end}

		   {week_row_start}<tr>{/week_row_start}
		   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
		   {week_row_end}</tr>{/week_row_end}

		   {cal_row_start}<tr>{/cal_row_start}
		   {cal_cell_start}<td>{/cal_cell_start}

		   {cal_cell_content}<a href="{content}">{day}</a>{/cal_cell_content}
		   {cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

		   {cal_cell_no_content}{day}{/cal_cell_no_content}
		   {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

		   {cal_cell_blank}&nbsp;{/cal_cell_blank}

		   {cal_cell_end}</td>{/cal_cell_end}
		   {cal_row_end}</tr>{/cal_row_end}

		   {table_close}</table>{/table_close}
		';

		$data['title'] = '일정';

		$this->load->library('calendar', $prefs);

		$this->load->view('layout/header', array('title' => $data['title']));		
		echo $this->calendar->generate();
		$this->load->view('layout/footer');

	}

} // END class Schedule extends CI_Controller
