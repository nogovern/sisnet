<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 장비 카테고리
|--------------------------------------------------------------------------
| 관리성 때문에 
| DB 를 이용하기로 함
|
| 자주 참조되므로 배열로 캐싱 해 놓으면 좋겠다
*/
$config['gs2']['category'] = array();

// 업무 형태 (대분류)
$config['gs2']['op_type'] = array(
	'100'	=> '입고',
	'200'	=> '설치',
	'201'	=> '설치-신규',
	'202'	=> '설치-휴점C',
	'203'	=> '설치-휴점S',
	'203'	=> '설치-리뉴얼',
	'300'	=> '철수',
	'301'	=> '철수-일반',
	'302'	=> '철수-휴점C',
	'303'	=> '철수-휴점S',
	'304'	=> '철수-리뉴얼',
	'400'	=> '',
	'500'	=> '',
	'900'	=> '상태변경',
);

// 점포 가맹 타입 (DB 에서 불러와야 함)
$config['gs2']['store_join_type'] = array(
	'1' => '가맹1종A(순수)',
	'2' => '가맹1종C(전대)',
	'3' => '직영A (일반)',
	'4' => '직영B',
	'5' => 'C타입',
	'6' => 'G타입',
	'7' => 'K타입',
	'8' => 'S타입',
);

$config['gs2']['close'] = array(
		
);

/* End of file sisnet.php */
/* Location: ./application/config/sisnet.php */