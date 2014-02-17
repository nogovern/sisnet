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
			'query'				=> $this->input->get(),
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

	/**
	 * 일정 데이터 반환
	 * 
	 * @param  integer $year     [description]
	 * @param  integer $month    [description]
	 * @param  array  $criteria [description]
	 * @return array           	[description]
	 */
	public function getCalnedarData($year, $month, $criteria = array()) {
		$start = new DateTime($year . $month . '01');
		$qb = $this->em->createQueryBuilder();
		$qb->select('w')
			->from('\Entity\Operation', 'w')
			->where("w.date_expect >= :from")
			->andWhere("w.date_expect < :to");

		// gs2_dump($criteria);

		// 사무소별
		if( $criteria['office'] != 'all' && $criteria['office'] > 0) {
			$qb->andWhere("w.office = :office");
			$qb->setParameter('office', $criteria['office']);
		}
		
		// 이번달
		$qb->orderBy('w.id', 'DESC')
			->setParameter('from', $start->format('Y-m-d'))
			->setParameter('to', $start->add(new DateInterval("P1M"))->format('Y-m-d'));

		$rows = $qb->getQuery()->getResult();

		// calendar data aary 로 변환
		$events = array();
		if(count($rows)) {
			foreach($rows as $row) {
				// 교체 메인은 안 보여줌
				if($row->type == '400') {
					continue;
				}

				// 진행 상태 별 색 지정
				if($row->status == '1') {
					$text_style = 'color:red;';
				} else if($row->status == '2') {
					$text_style = 'color:blue;';
				} else {
					$text_style = 'color:#666;';
				}

				$day = $row->date_expect->format("j");
				$content = sprintf("[%s]<a href=\"%s\" style=\"%s\">%s %s</a>", 
					$row->office->name, 
					gs2_hover($row->type) . $row->id, 
					$text_style,
					gs2_op_short_type($row->type),
					gs2_decode_location($row->work_location)->name 
				);

				if(array_key_exists($day, $events)){
					$content = $events[$day] . '<br>' . $content;
				} 		

				$events[$day] = $content;
			}
		}

		return $events;
	}

	public function addCalendarData($date, $data) {
		;
	}

	public function generate($year=null, $month=null) {
		$this->load->library('calendar', $this->config);

		// 검색 조건이 있을 경우
		$criteria = array();
		if($this->input->get('office') !== false) {
			$criteria['office'] = $this->input->get('office');
		} else {
			if( $this->session->userdata('user_type') == '1') {
				$criteria['office'] = $this->session->userdata('office_id');
			} else {
				$criteria['office'] = 'all';
			}
		}
		// gs2_dump($criteria);

		$data = $this->getCalnedarData($year, $month, $criteria);

		return $this->calendar->generate($year, $month, $data);
	}


}