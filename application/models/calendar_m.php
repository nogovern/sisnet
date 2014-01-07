<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/
class Calendar_m extends MY_Model
{
	var $config;
	
	function __construct()
	{
		parent::__construct();

		$this->setTableName('gs2_operations');
		$this->setEntityName('Operation');

		$this->config = array(
			'start_day'	=> 'sunday',
			'show_next_prev'	=> TRUE,
			'next_prev_url'		=> site_url('schedule/calendar/'),
			'month_type'		=> 'long',
			'day_type'			=> 'short',
			'template' => '
			{table_open}<table class="table table-bordered">{/table_open}

			{heading_row_start}<tr>{/heading_row_start}

			{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
			{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

			{heading_row_end}</tr>{/heading_row_end}

			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<th>{week_day}</th>{/week_day_cell}
			{week_row_end}</tr>{/week_row_end}

			{cal_row_start}<tr>{/cal_row_start}
			{cal_cell_start}<td>{/cal_cell_start}

			{cal_cell_content}
				<div>{day}</div>
				<div>{content}</div>
			{/cal_cell_content}

			{cal_cell_content_today}<div class="highlight"><a href="{content}">{day} {content}</a></div>{/cal_cell_content_today}

			{cal_cell_no_content}{day}{/cal_cell_no_content}
			{cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

			{cal_cell_blank}&nbsp;{/cal_cell_blank}

			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}

			{table_close}</table>{/table_close}
		');
	}

	public function getCalnedarData($year, $month) {
		;
	}

	public function addCalendarData($date, $data) {
		;
	}

	public function generate($year=null, $month=null) {
		$this->load->library('calendar', $this->config);

		$data = array('6' => 'Smaple day', '10' => 'Todo like this');

		return $this->calendar->generate($year, $month, $data);
	}


}