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
			{table_open}<table id="calendar" class="table table-bordered">{/table_open}

			{heading_row_start}<thead><tr>{/heading_row_start}

			{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
			{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

			{heading_row_end}</tr>{/heading_row_end}

			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<th width="14.28%">{week_day}</th>{/week_day_cell}
			{week_row_end}</tr></thead><tbody>{/week_row_end}

			{cal_row_start}<tr>{/cal_row_start}
			{cal_cell_start}<td>{/cal_cell_start}

			{cal_cell_content}
				<div><span class="badge">{day}</span></div>
				<div class="help-block">{content}</div>
			{/cal_cell_content}

			{cal_cell_content_today}
				<div class="highlight"><span class="badge">{day}</span></div>
				<div class="help-block">{content}</div>
			{/cal_cell_content_today}

			{cal_cell_no_content}{day}{/cal_cell_no_content}
			{cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

			{cal_cell_blank}&nbsp;{/cal_cell_blank}

			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}

			{table_close}</tbody></table>{/table_close}
		');
	}

	public function getCalnedarData($year, $month) {
		$start = new DateTime($year . $month . '01');
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where("w.date_request >= :from")
			->andWhere("w.date_request < :to")
			->orderBy('w.id', 'DESC')
			->setParameter('from', $start->format('Y-m-d'))
			->setParameter('to', $start->add(new DateInterval("P1M"))->format('Y-m-d'));

		$rows = $qb->getQuery()->getResult();

		// calendar data aary 로 변환
		$events = array();
		if(count($rows)) {
			foreach($rows as $row) {
				$day = $row->date_request->format("j");
				$content = sprintf("[%s] <a href=\"%s\">%s</a>", $row->office->name, '#', gs2_op_type($row->type));
				if(array_key_exists($day, $events)){
					$content = $events[$day] . '<br>' . $content;
				} 		

				$events[$day] = $content;
			}
		}
		// var_dump($events);

		return $events;
	}

	public function addCalendarData($date, $data) {
		;
	}

	public function generate($year=null, $month=null) {
		$this->load->library('calendar', $this->config);

		$data = $this->getCalnedarData($year, $month);
		// $data = array(
		// 	'6' => 'Smaple day', 
		// 	'10' => '1. enter<br>2. Install<br/>3. Evacuation',
		// 	'28' => '1. enter<br>2. Install<br/>3. Evacuation'
		// );

		return $this->calendar->generate($year, $month, $data);
	}


}